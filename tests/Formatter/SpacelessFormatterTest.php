<?php

namespace SitemapGenerator\Tests\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter\Spaceless as SpacelessFormatter;
use SitemapGenerator\SitemapFormatter;

class TestableSitemapFormatter implements SitemapFormatter
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
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('joe', $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('foo', $formatter->getSitemapEnd());
    }

    public function testFormatUrl()
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr', $formatter->formatUrl($url));
    }
}
