<?php

namespace SitemapGenerator;

use SitemapGenerator\Dumper\File;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\SitemapIndex;
use SitemapGenerator\Provider\DefaultValues;

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
class IndexedSitemap
{
    const MAX_ENTRIES_PER_SITEMAP = 50000;

    /**
     * @var SplObjectStorage
     */
    protected $providers;

    protected $dumper;
    private $formatter;
    private $limit = self::MAX_ENTRIES_PER_SITEMAP;
    private $indexEntries = [];
    private $originalFilename;
    private $currentSitemap;
    private $currentSitemapItemsCount = 0;

    /**
     * @param string $baseHostSitemap The base URL for the sitemap.
     * @param integer $limit The URL limit for each sitemap.
     */
    public function __construct(Dumper $dumper, SitemapIndexFormatter $formatter, $baseHostSitemap, $limit = self::MAX_ENTRIES_PER_SITEMAP)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;
        $this->baseHostSitemap = $baseHostSitemap;
        $this->limit = $limit;

        $this->providers = new \SplObjectStorage();
        $this->originalFilename = $dumper->getFilename();
    }

    public function addProvider(Provider $provider, DefaultValues $defaultValues = null)
    {
        $this->providers->attach($provider, $defaultValues ?: DefaultValues::none());

        return $this;
    }

    /**
     * @return string|null The sitemap's content if available.
     */
    public function build()
    {
        $this->addIndexEntry($this->createIndexEntry());

        $this->currentSitemap = new Sitemap($this->dumper, $this->formatter);
        $this->currentSitemapItemsCount = 0;

        foreach ($this->providers as $provider) {
            $defaultValues = $this->providers[$provider];

            foreach ($provider->getEntries() as $entry) {
                $this->add($entry, $defaultValues);
            }
        }

        $this->dumper->dump($this->formatter->getSitemapEnd());

        $this->dumper->setFilename($this->originalFilename);

        $this->dumper->dump($this->formatter->getSitemapIndexStart());
        foreach ($this->indexEntries as $sitemapIndex) {
            $this->dumper->dump($this->formatter->formatSitemapIndex($sitemapIndex));
        }

        $this->dumper->dump($this->formatter->getSitemapIndexEnd());
    }

    private function add(Url $url, DefaultValues $defaultValues)
    {
        if ($this->currentSitemapItemsCount >= $this->limit) {
            $this->currentSitemap = new Sitemap($this->dumper, $this->formatter);
            $this->currentSitemapItemsCount = 0;

            $this->addIndexEntry($this->createIndexEntry());
        }

        $this->currentSitemap->add($url, $defaultValues);

        $this->currentSitemapItemsCount += 1;
    }

    private function createIndexEntry()
    {
        $sitemapIndexFilename = $this->getSitemapIndexFilename($this->originalFilename);

        $sitemapIndex = new SitemapIndex($this->baseHostSitemap .'/'.basename($sitemapIndexFilename));
        $sitemapIndex->setLastmod(new \DateTime());

        return $sitemapIndex;
    }

    private function addIndexEntry(SitemapIndex $sitemapIndex)
    {
        $nbSitemapIndexs = count($this->indexEntries);

        if ($nbSitemapIndexs > 0) {
            // Close tag of the previous sitemapIndex
            $this->dumper->dump($this->formatter->getSitemapEnd());
        }

        // Modify the filename of the dumper, add the filename to the sitemap indexes
        $sitemapIndexFilename = $this->getSitemapIndexFilename($this->originalFilename);
        $this->dumper->setFilename($sitemapIndexFilename);

        $this->indexEntries[] = $sitemapIndex;
    }

    private function getSitemapIndexFilename($filename)
    {
        $sitemapIndexFilename = basename($filename);
        $index = count($this->indexEntries) + 1;
        $extPosition = strrpos($sitemapIndexFilename, '.');
        if ($extPosition !== false) {
            $sitemapIndexFilename = substr($sitemapIndexFilename, 0, $extPosition) . '-' . $index . substr($sitemapIndexFilename, $extPosition);
        } else {
            $sitemapIndexFilename .= '-' . $index;
        }

        $sitemapIndexFilename = dirname($filename) . DIRECTORY_SEPARATOR . $sitemapIndexFilename;

        return $sitemapIndexFilename;
    }
}
