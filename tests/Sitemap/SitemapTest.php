<?php

namespace SitemapGenerator\Tests\Sitemap;

use SitemapGenerator\Dumper;
use SitemapGenerator\Entity\Image;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\Video;
use SitemapGenerator\Formatter;
use SitemapGenerator\Provider\Provider;
use SitemapGenerator\Sitemap\Sitemap;

class TestableSitemap extends Sitemap
{
    public function getProviders()
    {
        return $this->providers;
    }

    public function getDumper()
    {
        return $this->dumper;
    }
}

class TestableProvider implements Provider
{
    public function getEntries()
    {
        $url = new Url();
        $url->setLoc('/search');

        return [$url];
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

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddUrlNoLoc()
    {
        $sitemap = new TestableSitemap(new Dumper\Memory(), new Formatter\Text(), 'http://www.google.fr');
        $url = new Url();
        $sitemap->add($url);
    }

    public function testAddUrlNoBaseHost()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new TestableSitemap($dumper, new Formatter\Text(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('/search');

        $sitemap->add($url);

        $this->assertSame('http://www.google.fr/search', $url->getLoc());
        $this->assertSame('http://www.google.fr/search' . "\n", $dumper->getBuffer());
    }

    public function testAddUrlBaseHost()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new TestableSitemap($dumper, new Formatter\Text(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('http://www.joe.fr/search');

        $sitemap->add($url);

        $this->assertSame('http://www.joe.fr/search', $url->getLoc());
        $this->assertSame('http://www.joe.fr/search' . "\n", $dumper->getBuffer());
    }

    public function testAddUrlBaseHostToImages()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new TestableSitemap($dumper, new Formatter\Text(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('http://www.joe.fr/search');

        $image = new Image();
        $image->setLoc('/thumbs/123.jpg');
        $image->setLicense('/lic/MIT.txt');

        $url->addImage($image);

        $sitemap->add($url);

        $this->assertSame('http://www.google.fr/thumbs/123.jpg', $image->getLoc());
        $this->assertSame('http://www.google.fr/lic/MIT.txt', $image->getLicense());
    }

    public function testAddUrlBaseHostToVideos()
    {
        $dumper = new Dumper\Memory();
        $sitemap = new TestableSitemap($dumper, new Formatter\Text(), 'http://www.google.fr');
        $url = new Url();
        $url->setLoc('http://www.joe.fr/search');

        $video = new Video();
        $video->setThumbnailLoc('/thumbs/123.jpg');
        $video->setContentLoc('/content/123.avi');
        $video->setPlayerLoc('/player/123.swf');
        $video->setGalleryLoc('/gallery/123');
        $url->addVideo($video);

        $sitemap->add($url);

        $this->assertSame('http://www.google.fr/thumbs/123.jpg', $video->getThumbnailLoc());
        $this->assertSame('http://www.google.fr/content/123.avi', $video->getContentLoc());
        $player =  $video->getPlayerLoc();
        $this->assertSame('http://www.google.fr/player/123.swf', $player['loc']);
        $gallery =  $video->getGalleryLoc();
        $this->assertSame('http://www.google.fr/gallery/123', $gallery['loc']);
    }

    public function testBuild()
    {
        $sitemap = new TestableSitemap(new Dumper\Memory(), new Formatter\Text(), 'http://www.google.fr');
        $sitemap->addProvider(new TestableProvider());

        $this->assertSame('http://www.google.fr/search' . "\n", $sitemap->build());
    }
}
