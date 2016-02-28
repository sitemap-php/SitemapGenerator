<?php

namespace SitemapGenerator\Tests;

use SitemapGenerator\Dumper;
use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;
use SitemapGenerator\Provider\DefaultValues;
use SitemapGenerator\Sitemap;

class SitemapTest extends \PHPUnit_Framework_TestCase
{
    public function testAddProvider()
    {
        $sitemap = new class($this->getDumper(), $this->getFormatter()) extends Sitemap {
            public function getProviders()
            {
                return $this->providers;
            }
        };
        $this->assertSame(0, count($sitemap->getProviders()));

        $sitemap->addProvider(new \ArrayIterator([]));
        $this->assertSame(1, count($sitemap->getProviders()));
    }

    public function testRelativeUrlsAreKeptIntact()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new class($dumper, new Formatter\Text()) extends Sitemap {
            public function testableAdd(Url $url)
            {
                $this->add($url, DefaultValues::none());
            }
        };
        $url = new Url('/search');

        $sitemap->testableAdd($url);

        $this->assertSame('/search', $url->getLoc());
        $this->assertSame('/search' . "\n", $dumper->getBuffer());
    }

    public function testAddWithDefaultValues()
    {
        $formatter = $this->getFormatter();
        $sitemap = new Sitemap($this->getDumper(), $formatter);
        $defaultValues = DefaultValues::create(0.7, ChangeFrequency::ALWAYS);

        $formatter
            ->expects($this->once())
            ->method('formatUrl')
            ->with($this->callback(function(Url $url) {
                return $url->getPriority() === 0.7 && $url->getChangefreq() === ChangeFrequency::ALWAYS;
            }));

        $sitemap->addProvider(new \ArrayIterator([new Url('http://www.google.fr/search')]), $defaultValues);
        $sitemap->build();
    }

    public function testBuild()
    {
        $sitemap = new Sitemap(new Dumper\Memory(), new Formatter\Text(), 'http://www.google.fr');
        $sitemap->addProvider(new \ArrayIterator([new Url('http://www.google.fr/search')]));

        $this->assertSame('http://www.google.fr/search' . "\n", $sitemap->build());
    }

    protected function getDumper()
    {
        return $this->getMock('SitemapGenerator\Dumper');
    }

    protected function getFormatter()
    {
        return $this->getMock('SitemapGenerator\SitemapFormatter');
    }
}
