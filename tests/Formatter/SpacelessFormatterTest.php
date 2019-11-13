<?php

namespace SitemapGenerator\Tests\Formatter;

use PHPUnit\Framework\TestCase;
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

class SpacelessFormatterTest extends TestCase
{
    public function testSitemapStart(): void
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('joe', $formatter->getSitemapStart());
    }

    public function testSitemapEnd(): void
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('foo', $formatter->getSitemapEnd());
    }

    public function testGetSitemapIndexStartWithSitemapFormatter(): void
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('', $formatter->getSitemapIndexStart());
    }

    public function testGetSitemapIndexStartWithSitemapIndexFormatter(): void
    {
        $sitemapIndexFormatter = $this->createMock(SitemapIndexFormatter::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('getSitemapIndexStart')
            ->will($this->returnValue("\tsome value with spaces\n"));

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some value with spaces', $formatter->getSitemapIndexStart());
    }

    public function testGetSitemapIndexEndWithSitemapFormatter(): void
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $this->assertSame('', $formatter->getSitemapIndexEnd());
    }

    public function testGetSitemapIndexEndWithSitemapIndexFormatter(): void
    {
        $sitemapIndexFormatter = $this->createMock(SitemapIndexFormatter::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('getSitemapIndexEnd')
            ->willReturn("\tsome value with spaces\n");

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some value with spaces', $formatter->getSitemapIndexEnd());
    }

    public function testFormatSitemapIndexWithSitemapFormatter(): void
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());
        $entry = new SitemapIndexEntry('not relevant');

        $this->assertSame('', $formatter->formatSitemapIndex($entry));
    }

    public function testFormatSitemapIndexWithSitemapIndexFormatter(): void
    {
        $entry = new SitemapIndexEntry('not relevant');

        $sitemapIndexFormatter = $this->createMock(SitemapIndexFormatter::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('formatSitemapIndex')
            ->with($entry)
            ->willReturn("\tsome url\n");

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some url', $formatter->formatSitemapIndex($entry));
    }

    public function testFormatUrl(): void
    {
        $formatter = new SpacelessFormatter(new TestableSitemapFormatter());

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr', $formatter->formatUrl($url));
    }
}
