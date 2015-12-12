<?php

namespace SitemapGenerator\Dumper;

/**
 * The dumper takes care of the sitemap's persistance (file, compressed file,
 * memory).
 */
interface Dumper
{
    /**
     * Dump a string.
     *
     * @param string $string The string to dump.
     */
    public function dump($string);
}
