<?php

namespace SitemapGenerator\Tests\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;

class TestableSitemapFormatter implements Formatter\Sitemap
{
    public function getSitemapStart()
    {
        return "\tjoe\n";
    }

    public function getSitemapEnd()
    {
        return "\tfoo\n";
    }

    public function formatUrl(Url $url)
    {
        return sprintf("\t%s\n", $url->getLoc());
    }
}

class SpacelessFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testSitemapStart()
    {
        $formatter = new Formatter\Spaceless(new TestableSitemapFormatter());
        $this->assertSame('joe', $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new Formatter\Spaceless(new TestableSitemapFormatter());
        $this->assertSame('foo', $formatter->getSitemapEnd());
    }

    public function testFormatUrl()
    {
        $formatter = new Formatter\Spaceless(new TestableSitemapFormatter());

        $url = new Url();
        $url->setLoc('http://www.google.fr');

        $this->assertSame('http://www.google.fr', $formatter->formatUrl($url));
    }
}
