<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Sitemap\Sitemap;

/**
 * Providers are responsible for adding Url's into the sitemap.
 */
interface Provider
{
    /**
     * List the entries to add to the sitemap.
     *
     * @return \Traversable
     */
    public function getEntries();
}
