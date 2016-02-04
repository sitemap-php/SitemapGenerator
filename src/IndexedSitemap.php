<?php

namespace SitemapGenerator;

use iter;
use SitemapGenerator\Dumper\File as FileDumper;
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

    protected $providers = [];

    protected $dumper;
    private $formatter;
    private $limit = self::MAX_ENTRIES_PER_SITEMAP;
    private $originalFilename;

    /**
     * @param string $baseHostSitemap The base URL for the sitemap.
     * @param integer $limit The URL limit for each sitemap.
     */
    public function __construct(FileDumper $dumper, SitemapIndexFormatter $formatter, $baseHostSitemap, $limit = self::MAX_ENTRIES_PER_SITEMAP)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;
        $this->baseHostSitemap = $baseHostSitemap;
        $this->limit = $limit;

        $this->providers = [];
        $this->originalFilename = $dumper->getFilename();
    }

    public function addProvider(\Traversable $provider)
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * @return string|null The sitemap's content if available.
     */
    public function build()
    {
        $chunkedProviders = iter\chunk(iter\chain(...$this->providers), $this->limit);
        $entries = [];

        foreach ($chunkedProviders as $i => $provider) {
            // Modify the filename of the dumper, add the filename to the sitemap indexes
            $entryFilename = $this->getSitemapIndexFilename($this->originalFilename, $i+1);
            $this->dumper->setFilename($entryFilename);

            // keep the entry for later
            $entries[] = $this->createIndexEntry($entryFilename, $i+1);

            // dump the sitemap
            $sitemap = new Sitemap($this->dumper, $this->formatter);
            $sitemap->addProvider(new \ArrayIterator($provider));
            $sitemap->build();
        }

        // dump the sitemap index
        $this->dumper->setFilename($this->originalFilename);
        $this->dumper->dump($this->formatter->getSitemapIndexStart());
        foreach ($entries as $sitemapIndex) {
            $this->dumper->dump($this->formatter->formatSitemapIndex($sitemapIndex));
        }

        $this->dumper->dump($this->formatter->getSitemapIndexEnd());
    }

    private function createIndexEntry($sitemapFilename, $index)
    {
        return new SitemapIndex($this->baseHostSitemap .'/'.basename($sitemapFilename), new \DateTime());
    }

    private function getSitemapIndexFilename($filename, $index)
    {
        $sitemapIndexFilename = basename($filename);
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
