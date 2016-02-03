<?php

namespace SitemapGenerator;

use SitemapGenerator\Entity\Url;

interface SitemapFormatter
{
    public function getSitemapStart();
    public function getSitemapEnd();
    public function formatUrl(Url $url);
}
