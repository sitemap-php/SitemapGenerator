<?php

declare(strict_types=1);

namespace SitemapGenerator;

use iter;
use SitemapGenerator\Entity\SitemapIndexEntry;

/**
 * Sitemap index generator.
 *
 * It will use a set of providers to build the sitemaps and the index.
 * The dumper takes care of the sitemap's persistence (file, compressed file,
 * memory) and the formatter formats it.
 *
 * The whole process tries to be as memory-efficient as possible, that's why URLs
 * are not stored but dumped immediately.
 */
final class SitemapIndex
{
    public const MAX_ENTRIES_PER_SITEMAP = 50000;

    private $providers = [];

    private $baseHostSitemap;
    private $dumper;
    private $formatter;
    private $limit;

    /**
     * @param string $baseHostSitemap The base URL for the sitemap.
     * @param int $limit The maximum number of URL for each sitemap.
     */
    public function __construct(FileDumper $dumper, SitemapIndexFormatter $formatter, string $baseHostSitemap, int $limit = self::MAX_ENTRIES_PER_SITEMAP)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;
        $this->baseHostSitemap = $baseHostSitemap;
        $this->limit = $limit;
    }

    /**
     * @param \Traversable $provider A set of iterable Url objects.
     */
    public function addProvider(\Traversable $provider): void
    {
        $this->providers[] = $provider;
    }

    public function build(): void
    {
        // dump the sitemap index start tag
        $this->dumper->dump($this->formatter->getSitemapIndexStart());

        $chunkedProviders = $this->chunk(iter\chain(...$this->providers), $this->limit);
        foreach ($chunkedProviders as $i => $provider) {
            // Modify the filename of the dumper, add the filename to the sitemap indexes
            $entryFilename = $this->getSitemapIndexFilename($this->dumper->getFilename(), $i + 1);

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

    private function createIndexEntry(string $sitemapFilename): SitemapIndexEntry
    {
        return new SitemapIndexEntry($this->baseHostSitemap .'/'.basename($sitemapFilename), new \DateTimeImmutable());
    }

    private function getSitemapIndexFilename(string $filename, int $index): string
    {
        $sitemapIndexFilename = basename($filename);
        $extPosition = strrpos($sitemapIndexFilename, '.');
        if ($extPosition !== false) {
            $sitemapIndexFilename = substr($sitemapIndexFilename, 0, $extPosition) . '-' . $index . substr($sitemapIndexFilename, $extPosition);
        } else {
            $sitemapIndexFilename .= '-' . $index;
        }

        return \dirname($filename) . DIRECTORY_SEPARATOR . $sitemapIndexFilename;
    }

    private function chunk(\Iterator $iterable, $size): \Iterator
    {
        while ($iterable->valid()) {
            $closure = function () use ($iterable, $size) {
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
