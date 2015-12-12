<?php

namespace SitemapGenerator\Dumper;

/**
 * Dump the sitemap into a compressed file.
 *
 * @see \SitemapGenerator\Dumper\File
 */
class GzFile extends File
{
    public function __construct($filename)
    {
        parent::__construct('compress.zlib://'.$filename);
    }
}
