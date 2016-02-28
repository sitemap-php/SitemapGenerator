<?php

namespace SitemapGenerator\Tests\Entity;

use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Entity\Image;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\Video;

class UrlTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \DomainException
     */
    public function testLocMaxLength()
    {
        new Url('http://google.fr/?q=' . str_repeat('o', 2048));
    }

    /**
     * @dataProvider invalidPriorityProvider
     * @expectedException \DomainException
     */
    public function testInvalidPriority($priority)
    {
        $url = new Url('http://www.google.fr/');
        $url->setPriority($priority);
    }

    /**
     * @expectedException \DomainException
     */
    public function testInvalidChangefreq()
    {
        $url = new Url('http://www.google.fr/');
        $url->setChangefreq('foo');
    }

    /**
     * @dataProvider changefreqProvider
     */
    public function testChangefreq($changefreq)
    {
        $url = new Url('http://www.google.fr/');
        $url->setChangefreq($changefreq);

        $this->assertSame($changefreq, $url->getChangefreq());
    }

    /**
     * @dataProvider lastmodProvider
     */
    public function testLastmodFormatting($lastmod, $changefreq, $expected_lastmod)
    {
        $url = new Url('http://www.google.fr/');
        $url->setLastmod($lastmod);
        $url->setChangefreq($changefreq);

        $this->assertSame($expected_lastmod, $url->getLastmod());
    }

    public function testImages()
    {
        $url = new Url('http://www.google.fr/');
        $image = new Image();

        $url->setImages([$image]);

        $this->assertSame([$image], $url->getImages());
    }

    public function testVideos()
    {
        $url = new Url('http://www.google.fr/');
        $video = new Video();

        $url->setVideos([$video]);

        $this->assertSame([$video], $url->getVideos());
    }

    public function lastmodProvider()
    {
        return [
            [null, null, null],
            [null, ChangeFrequency::YEARLY, null],
            [new \DateTime('2012-12-20 18:44'), null, $this->dateFormatW3C('2012-12-20 18:44')],
            [new \DateTime('2012-12-20 18:44'), ChangeFrequency::HOURLY, $this->dateFormatW3C('2012-12-20 18:44')],
            [new \DateTime('2012-12-20 18:44'), ChangeFrequency::ALWAYS, $this->dateFormatW3C('2012-12-20 18:44')],
            [new \DateTime('2012-12-20 18:44'), ChangeFrequency::DAILY, '2012-12-20'],
        ];
    }

    public function changefreqProvider()
    {
        return [
            [null],
            [ChangeFrequency::ALWAYS],
            [ChangeFrequency::HOURLY],
            [ChangeFrequency::DAILY],
            [ChangeFrequency::WEEKLY],
            [ChangeFrequency::MONTHLY],
            [ChangeFrequency::YEARLY],
            [ChangeFrequency::NEVER],
        ];
    }

    public function invalidPriorityProvider()
    {
        return [
            [-0.1],
            [1.1],
        ];
    }

    protected function dateFormatW3C($date)
    {
        return (new \DateTime($date))->format(\DateTime::W3C);
    }
}
