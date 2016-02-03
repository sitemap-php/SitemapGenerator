<?php

namespace SitemapGenerator;

use SitemapGenerator\Entity;

interface SitemapIndexFormatter extends SitemapFormatter
{
    public function getSitemapIndexStart();
    public function getSitemapIndexEnd();
    public function formatSitemapIndex(Entity\SitemapIndex $sitemapIndex);
}
