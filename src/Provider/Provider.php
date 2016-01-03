<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Sitemap\Sitemap;

/**
 * Providers are responsible for adding Url's into the sitemap.
 */
interface Provider
{
    /**
     * Populate a sitemap.
     *
     * @param Sitemap $sitemap The current sitemap.
     */
    public function populate(Sitemap $sitemap);
}
