<?php

namespace SitemapGenerator\Tests\Sitemap;

use SitemapGenerator\Dumper;
use SitemapGenerator\Entity\Image;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\Video;
use SitemapGenerator\Formatter;
use SitemapGenerator\Provider;
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

    public function testAddUrlBaseHost()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new TestableSitemap($dumper, new Formatter\Text(), 'http://www.google.fr');
        $url = new Url('http://www.joe.fr/search');

        $sitemap->testableAdd($url);

        $this->assertSame('http://www.joe.fr/search', $url->getLoc());
        $this->assertSame('http://www.joe.fr/search' . "\n", $dumper->getBuffer());
    }

    public function testBuild()
    {
        $sitemap = new TestableSitemap(new Dumper\Memory(), new Formatter\Text(), 'http://www.google.fr');
        $sitemap->addProvider(new TestableProvider());

        $this->assertSame('http://www.google.fr/search' . "\n", $sitemap->build());
    }
}
