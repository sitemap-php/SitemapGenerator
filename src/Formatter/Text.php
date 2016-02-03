<?php

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\SitemapFormatter;

/**
 * Sitemaps formatted using this class will contain only one URL per line and
 * no other information.
 *
 * @see http://www.sitemaps.org/protocol.html#otherformats
 */
class Text implements SitemapFormatter
{
    public function getSitemapStart()
    {
        return '';
    }

    public function getSitemapEnd()
    {
        return '';
    }

    public function formatUrl(Url $url)
    {
        return $url->getLoc() . "\n";
    }
}
