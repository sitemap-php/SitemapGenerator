<?php

namespace SitemapGenerator;

use SitemapGenerator\Entity;

interface SitemapFormatter
{
    public function getSitemapStart();
    public function getSitemapEnd();
    public function formatUrl(Entity\Url $url);
}
