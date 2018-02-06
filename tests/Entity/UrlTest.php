<?php

namespace SitemapGenerator\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Entity\Image;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\Video;

class UrlTest extends TestCase
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
        $url->setChangeFreq('foo');
    }

    /**
     * @dataProvider changefreqProvider
     */
    public function testChangefreq($changefreq)
    {
        $url = new Url('http://www.google.fr/');
        $url->setChangeFreq($changefreq);

        $this->assertSame($changefreq, $url->getChangeFreq());
    }

    /**
     * @dataProvider lastmodProvider
     */
    public function testLastmodFormatting($lastmod, $changefreq, $expectedLastmod)
    {
        $url = new Url('http://www.google.fr/');
        $url->setLastmod($lastmod);
        $url->setChangeFreq($changefreq);

        $this->assertSame($expectedLastmod, $url->getLastmod());
    }

    public function testImages()
    {
        $url = new Url('http://www.google.fr/');
        $image = new Image('https://www.google.fr/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');

        $url->setImages([$image]);

        $this->assertSame([$image], $url->getImages());
    }

    public function testVideos()
    {
        $url = new Url('http://www.google.fr/');
        $video = new Video('Title', 'Description.', 'https://thumbnail.loc/img.jpg');

        $url->setVideos([$video]);

        $this->assertSame([$video], $url->getVideos());
    }

    public function lastmodProvider()
    {
        return [
            [new \DateTime('2012-12-20 18:44'), ChangeFrequency::HOURLY, $this->dateFormatW3C('2012-12-20 18:44')],
            [new \DateTime('2012-12-20 18:44'), ChangeFrequency::ALWAYS, $this->dateFormatW3C('2012-12-20 18:44')],
            [new \DateTime('2012-12-20 18:44'), ChangeFrequency::DAILY, '2012-12-20'],
        ];
    }

    public function changefreqProvider()
    {
        return [
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

    private function dateFormatW3C($date)
    {
        return (new \DateTime($date))->format(\DateTime::W3C);
    }
}
