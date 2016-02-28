<?php

namespace SitemapGenerator\Tests;

use SitemapGenerator\Dumper;
use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;
use SitemapGenerator\Provider\DefaultValues;
use SitemapGenerator\Sitemap;

class TestableSitemap extends Sitemap
{
    public function testableAdd(Url $url)
    {
        $this->add($url, DefaultValues::none());
    }

    public function getProviders()
    {
        return $this->providers;
    }

    public function getDumper()
    {
        return $this->dumper;
    }
}

class TestableProvider implements \IteratorAggregate
{
    public function getIterator()
    {
        yield new Url('http://www.google.fr/search');
    }
}

class SitemapTest extends \PHPUnit_Framework_TestCase
{
    public function testAddProvider()
    {
        $sitemap = new TestableSitemap(new Dumper\Memory(), new Formatter\Text());
        $this->assertSame(0, count($sitemap->getProviders()));

        $sitemap->addProvider(new TestableProvider());
        $this->assertSame(1, count($sitemap->getProviders()));
    }

    public function testRelativeUrlsAreKeptIntact()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new TestableSitemap($dumper, new Formatter\Text());
        $url = new Url('/search');

        $sitemap->testableAdd($url);

        $this->assertSame('/search', $url->getLoc());
        $this->assertSame('/search' . "\n", $dumper->getBuffer());
    }

    public function testAddWithDefaultValues()
    {
        $formatter = $this->getMock('SitemapGenerator\SitemapFormatter');
        $sitemap = new TestableSitemap($this->getMock('SitemapGenerator\Dumper'), $formatter);
        $defaultValues = DefaultValues::create(0.7, ChangeFrequency::ALWAYS);

        $formatter
            ->expects($this->once())
            ->method('formatUrl')
            ->with($this->callback(function(Url $url) {
                return $url->getPriority() === 0.7 && $url->getChangefreq() === ChangeFrequency::ALWAYS;
            }));

        $sitemap->addProvider(new TestableProvider(), $defaultValues);
        $sitemap->build();
    }

    public function testBuild()
    {
        $sitemap = new TestableSitemap(new Dumper\Memory(), new Formatter\Text(), 'http://www.google.fr');
        $sitemap->addProvider(new TestableProvider());

        $this->assertSame('http://www.google.fr/search' . "\n", $sitemap->build());
    }
}
