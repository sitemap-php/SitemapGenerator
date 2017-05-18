<?php

namespace SitemapGenerator\Tests\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\SitemapIndexEntry;
use SitemapGenerator\Formatter\Spaceless as SpacelessFormatter;
use SitemapGenerator\SitemapFormatter;
use SitemapGenerator\SitemapIndexFormatter;

class TestableSitemapFormatter implements SitemapFormatter
{
    public function getSitemapStart(): string
    {
        return "\tjoe\n";
    }

    public function getSitemapEnd(): string
    {
        return "\tfoo\n";
    }

    public function formatUrl(Url $url): string
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

    public function testGetSitemapIndexStartWithSitemapFormatter()
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('', $formatter->getSitemapIndexStart());
    }

    public function testGetSitemapIndexStartWithSitemapIndexFormatter()
    {
        $sitemapIndexFormatter = $this->createMock(SitemapIndexFormatter::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('getSitemapIndexStart')
            ->will($this->returnValue("\tsome value with spaces\n"));

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some value with spaces', $formatter->getSitemapIndexStart());
    }

    public function testGetSitemapIndexEndWithSitemapFormatter()
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('', $formatter->getSitemapIndexEnd());
    }

    public function testGetSitemapIndexEndWithSitemapIndexFormatter()
    {
        $sitemapIndexFormatter = $this->createMock(SitemapIndexFormatter::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('getSitemapIndexEnd')
            ->will($this->returnValue("\tsome value with spaces\n"));

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some value with spaces', $formatter->getSitemapIndexEnd());
    }

    public function testFormatSitemapIndexWithSitemapFormatter()
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $entry = new SitemapIndexEntry('not relevant');

        $this->assertSame('', $formatter->formatSitemapIndex($entry));
    }

    public function testFormatSitemapIndexWithSitemapIndexFormatter()
    {
        $entry = new SitemapIndexEntry('not relevant');

        $sitemapIndexFormatter = $this->createMock(SitemapIndexFormatter::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('formatSitemapIndex')
            ->with($entry)
            ->will($this->returnValue("\tsome url\n"));

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some url', $formatter->formatSitemapIndex($entry));
    }

    public function testFormatUrl()
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr', $formatter->formatUrl($url));
    }
}
