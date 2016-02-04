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
class Sitemap
{
    const STATUS_NEW = 'new';
    const STATUS_OPENED = 'opened';
    const STATUS_BUILT = 'build';

    /**
     * @var SplObjectStorage
     */
    protected $providers;

    protected $dumper = null;
    private $formatter = null;

    private $status = self::STATUS_NEW;

    public function __construct(Dumper $dumper, SitemapFormatter $formatter)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;

        $this->providers = new \SplObjectStorage();
    }

    public function addProvider(\Traversable $provider, DefaultValues $defaultValues = null)
    {
        $this->providers->attach($provider, $defaultValues ?: DefaultValues::none());

        return $this;
    }

    /**
     * @return string|null The sitemap's content if available.
     */
    public function build()
    {
        if ($this->status === self::STATUS_BUILT) {
            throw new \LogicException('This sitemap has already been built.');
        }

        foreach ($this->providers as $provider) {
            $defaultValues = $this->providers[$provider];

            foreach ($provider as $entry) {
                $this->add($entry, $defaultValues);
            }
        }

        return $this->dumper->dump($this->formatter->getSitemapEnd());
    }

    public function finish()
    {
        $this->status = self::STATUS_BUILT;

        return $this->dumper->dump($this->formatter->getSitemapEnd());
    }

    /**
     * @param Url $url The URL to add. If the URL is relative, the base host will be prepended.
     */
    public function add(Url $url, DefaultValues $defaultValues = null)
    {
        if ($this->status === self::STATUS_NEW) {
            $this->dumper->dump($this->formatter->getSitemapStart());
            $this->status = self::STATUS_OPENED;
        } else if ($this->status === self::STATUS_BUILT) {
            throw new \LogicException('This sitemap has already been built.');
        }

        $defaultValues = $defaultValues ?: DefaultValues::none();

        $loc = $url->getLoc();

        if (!$url->getPriority() && $defaultValues->hasPriority()) {
            $url->setPriority($defaultValues->getPriority());
        }

        if (!$url->getChangefreq() && $defaultValues->hasChangeFreq()) {
            $url->setChangefreq($defaultValues->getChangeFreq());
        }

        $this->dumper->dump($this->formatter->formatUrl($url));

        return $this;
    }
}
