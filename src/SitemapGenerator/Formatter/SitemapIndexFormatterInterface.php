<?php

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity\SitemapIndex;


interface SitemapIndexFormatterInterface extends FormatterInterface
{
    public function getSitemapIndexStart();
    public function getSitemapIndexEnd();
    public function formatSitemapIndex(SitemapIndex $sitemapIndex);
}
