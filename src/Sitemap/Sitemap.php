<?php

namespace SitemapGenerator\Sitemap;

use SitemapGenerator\Dumper\Dumper;
use SitemapGenerator\Dumper\File;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\SitemapIndex;
use SitemapGenerator\Formatter;
use SitemapGenerator\Provider\DefaultValues;
use SitemapGenerator\Provider\Provider;

/**
 * Sitemap generator.
 *
 * It will use a set of providers to build the sitemap.
 * The dumper takes care of the sitemap's persistance (file, compressed file,
 * memory) and the formatter formats it.
 *
 * The whole process tries to be as memory-efficient as possible, that's why URLs
 * are not stored but dumped immediatly.
 */
class Sitemap
{
    /**
     * @var SplObjectStorage
     */
    protected $providers;

    /**
     * @var Provider
     */
    protected $currentProvider;

    protected $dumper = null;
    protected $formatter = null;
    protected $baseHost = null;
    protected $limit = 0;
    protected $sitemapIndexes = [];
    protected $originalFilename = null;

    /**
     * @param string $baseHost The base URL for all the links (well only be used for relative URLs).
     * @param string $baseHostSitemap The base URL for the sitemap.
     * @param integer $limit The URL limit for each sitemap (only used in a sitemap index context)
     */
    public function __construct(Dumper $dumper, Formatter\Sitemap $formatter, $baseHost = null, $baseHostSitemap = null, $limit = 0)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;
        $this->baseHost = $baseHost;
        $this->baseHostSitemap = $baseHostSitemap;
        $this->limit = $limit;

        $this->providers = new \SplObjectStorage();

        if ($this->isSitemapIndexable()) {
            $this->originalFilename = $dumper->getFilename();
        }
    }

    public function addProvider(Provider $provider, DefaultValues $defaultValues = null)
    {
        $this->providers->attach($provider, $defaultValues ?: DefaultValues::empty());

        return $this;
    }

    public function setDumper(Dumper $dumper)
    {
        $this->dumper = $dumper;

        return $this;
    }

    /**
     * @return string|null The sitemap's content if available.
     */
    public function build()
    {
        if ($this->isSitemapIndexable()) {
            $this->addSitemapIndex($this->createSitemapIndex());
        }

        $this->dumper->dump($this->formatter->getSitemapStart());

        foreach ($this->providers as $provider) {
            $this->currentProvider = $provider;
            $provider->populate($this);
        }

        $sitemapContent = $this->dumper->dump($this->formatter->getSitemapEnd());

        if (!$this->isSitemapIndexable()) {
            return $sitemapContent;
        }

        if (count($this->sitemapIndexes)) {
            $this->dumper->setFilename($this->originalFilename);
            $this->dumper->dump($this->formatter->getSitemapIndexStart());
            foreach ($this->sitemapIndexes as $sitemapIndex) {
                $this->dumper->dump($this->formatter->formatSitemapIndex($sitemapIndex));
            }

            $this->dumper->dump($this->formatter->getSitemapIndexEnd());
        }
    }

    /**
     * @param Url $url The URL to add. If the URL is relative, the base host will be prepended.
     */
    public function add(Url $url)
    {
        $defaultValues = $this->currentProvider ? $this->providers[$this->currentProvider] : DefaultValues::empty();

        if ($this->isSitemapIndexable() && $this->getCurrentSitemapIndex()->getUrlCount() >= $this->limit) {
            $this->addSitemapIndex($this->createSitemapIndex());
        }

        $loc = $url->getLoc();
        if (empty($loc)) {
            throw new \InvalidArgumentException('The url MUST have a loc attribute');
        }

        if (!$url->getPriority() && $defaultValues->hasPriority()) {
            $url->setPriority($defaultValues->hasPriority());
        }

        if (!$url->getChangefreq() && $defaultValues->hasChangeFreq()) {
            $url->setChangefreq($defaultValues->getChangeFreq());
        }

        if ($this->baseHost !== null) {
            if ($this->needHost($loc)) {
                $url->setLoc($this->baseHost . $loc);
            }

            foreach ($url->getVideos() as $video) {
                if ($this->needHost($video->getThumbnailLoc())) {
                    $video->setThumbnailLoc($this->baseHost . $video->getThumbnailLoc());
                }

                if ($this->needHost($video->getContentLoc())) {
                    $video->setContentLoc($this->baseHost . $video->getContentLoc());
                }

                $player = $video->getPlayerLoc();
                if ($player !== null && $this->needHost($player['loc'])) {
                    $video->setPlayerLoc($this->baseHost . $player['loc'], $player['allow_embed'], $player['autoplay']);
                }

                $gallery = $video->getGalleryLoc();
                if ($gallery !== null && $this->needHost($gallery['loc'])) {
                    $video->setGalleryLoc($this->baseHost . $gallery['loc'], $gallery['title']);
                }
            }

            foreach ($url->getImages() as $image) {
                if ($this->needHost($image->getLoc())) {
                    $image->setLoc($this->baseHost . $image->getLoc());
                }

                if ($this->needHost($image->getLicense())) {
                    $image->setLicense($this->baseHost . $image->getLicense());
                }
            }
        }

        $this->dumper->dump($this->formatter->formatUrl($url));

        if ($this->isSitemapIndexable()) {
            $this->getCurrentSitemapIndex()->incrementUrl();
        }

        return $this;
    }

    protected function needHost($url)
    {
        if ($url === null) {
            return false;
        }

        return substr($url, 0, 4) !== 'http';
    }

    protected function isSitemapIndexable()
    {
        return ($this->limit > 0 && $this->dumper instanceof File && $this->formatter instanceof Formatter\SitemapIndex);
    }

    protected function createSitemapIndex()
    {
        $sitemapIndexFilename = $this->getSitemapIndexFilename($this->originalFilename);
        $sitemapIndex = new SitemapIndex();
        $loc = DIRECTORY_SEPARATOR . basename($sitemapIndexFilename);
        if ($this->baseHostSitemap !== null) {
            $sitemapIndex->setLoc($this->baseHostSitemap . $loc);
        }

        $sitemapIndex->setLastmod(new \DateTime());

        return $sitemapIndex;
    }

    protected function addSitemapIndex(SitemapIndex $sitemapIndex)
    {
        $nbSitemapIndexs = count($this->sitemapIndexes);

        if ($nbSitemapIndexs > 0) {
            // Close tag of the previous sitemapIndex
            $this->dumper->dump($this->formatter->getSitemapEnd());
        }

        // Modify the filename of the dumper, add the filename to the sitemap indexes
        $sitemapIndexFilename = $this->getSitemapIndexFilename($this->originalFilename);
        $this->dumper->setFilename($sitemapIndexFilename);

        $this->sitemapIndexes[] = $sitemapIndex;
        if ($nbSitemapIndexs > 0) {
            // Start tag of the new sitemapIndex
            $this->dumper->dump($this->formatter->getSitemapStart());
        }
    }

    protected function getCurrentSitemapIndex()
    {
        return end($this->sitemapIndexes);
    }

    protected function getSitemapIndexFilename($filename)
    {
        $sitemapIndexFilename = basename($filename);
        $index = count($this->sitemapIndexes) + 1;
        $extPosition = strrpos($sitemapIndexFilename, ".");
        if ($extPosition !== false) {
            $sitemapIndexFilename = substr($sitemapIndexFilename, 0, $extPosition) . '-' . $index . substr($sitemapIndexFilename, $extPosition);
        } else {
            $sitemapIndexFilename .= '-' . $index;
        }

        $sitemapIndexFilename = dirname($filename) . DIRECTORY_SEPARATOR . $sitemapIndexFilename;

        return $sitemapIndexFilename;
    }
}
