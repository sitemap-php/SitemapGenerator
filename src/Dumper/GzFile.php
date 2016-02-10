<?php

declare(strict_types=1);

namespace SitemapGenerator\Dumper;

use SitemapGenerator\FileDumper;

/**
 * Dump the sitemap into a compressed file.
 *
 * @see \SitemapGenerator\Dumper\File
 */
class GzFile extends File
{
    public function __construct(string $filename)
    {
        parent::__construct('compress.zlib://' . $filename);
    }

    /**
     * {@inheritdoc}
     */
    public function changeFile(string $filename): FileDumper
    {
        return new static(str_replace('compress.zlib://', '', $filename));
    }
}
