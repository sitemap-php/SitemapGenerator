<?php

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity\Url;

interface FormatterInterface
{
    public function getSitemapStart();
    public function getSitemapEnd();
    public function formatUrl(Url $url);
}
