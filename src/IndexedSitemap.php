<?php

namespace SitemapGenerator;

use iter;
use SitemapGenerator\Dumper\FileDumper;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\SitemapIndex;
use SitemapGenerator\Provider\DefaultValues;

/**
 * Sitemap index generator.
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

    protected $baseHostSitemap;
    protected $dumper;
    private $formatter;
    private $limit = self::MAX_ENTRIES_PER_SITEMAP;

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
    }

    /**
     * @param \Traversable $provider A set of iterable Url objects.
     */
    public function addProvider(\Traversable $provider)
    {
        $this->providers[] = $provider;
    }

    public function build()
    {
        // dump the sitemap index start tag
        $this->dumper->dump($this->formatter->getSitemapIndexStart());

        $chunkedProviders = $this->chunk(iter\chain(...$this->providers), $this->limit);
        foreach ($chunkedProviders as $i => $provider) {
            // Modify the filename of the dumper, add the filename to the sitemap indexes
            $entryFilename = $this->getSitemapIndexFilename($this->dumper->getFilename(), $i+1);

            // dump the entry in the sitemap index
            $entry = $this->createIndexEntry($entryFilename);
            $this->dumper->dump($this->formatter->formatSitemapIndex($entry));

            // dump the sitemap entry itself
            $sitemap = new Sitemap($this->dumper->changeFile($entryFilename), $this->formatter);
            $sitemap->addProvider($provider);
            $sitemap->build();
        }

        // dump the sitemap index end tag
        $this->dumper->dump($this->formatter->getSitemapIndexEnd());
    }

    private function createIndexEntry($sitemapFilename)
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

    private function chunk(\Iterator $iterable, $size) {
        while ($iterable->valid()) {
            $closure = function() use ($iterable, $size) {
                $count = $size;

                while ($count-- && $iterable->valid()) {
                    yield $iterable->current();

                    $iterable->next();
                }
            };

            yield $closure();
        }
    }
}
