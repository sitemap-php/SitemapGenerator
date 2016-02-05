<?php

namespace SitemapGenerator;

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
     *
     * @return void|string The dumped content (if available/relevant) or nothing.
     */
    public function dump($string);
}
