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
    /**
     * @var SplObjectStorage
     */
    protected $providers;

    protected $dumper = null;
    private $formatter = null;

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
        $this->dumper->dump($this->formatter->getSitemapStart());

        foreach ($this->providers as $provider) {
            $defaultValues = $this->providers[$provider];

            foreach ($provider as $entry) {
                $this->add($entry, $defaultValues);
            }
        }

        return $this->dumper->dump($this->formatter->getSitemapEnd());
    }

    protected function add(Url $url, DefaultValues $defaultValues)
    {
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
