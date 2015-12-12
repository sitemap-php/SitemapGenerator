<?php

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity;

interface SitemapIndex extends Sitemap
{
    public function getSitemapIndexStart();
    public function getSitemapIndexEnd();
    public function formatSitemapIndex(Entity\SitemapIndex $sitemapIndex);
}
