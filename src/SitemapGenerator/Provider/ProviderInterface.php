<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Sitemap\Sitemap;

/**
 * Describe a provider.
 *
 * Providers are responsible for adding Url's into the sitemap.
 */
interface ProviderInterface
{
    /**
     * Populate a sitemap.
     *
     * @param Sitemap $sitemap The current sitemap.
     */
    public function populate(Sitemap $sitemap);
}
