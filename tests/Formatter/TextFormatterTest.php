<?php

namespace SitemapGenerator\Tests\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;

class TextFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testSitemapStart()
    {
        $formatter = new Formatter\Text();
        $this->assertEquals('', $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new Formatter\Text();
        $this->assertEquals('', $formatter->getSitemapEnd());
    }

    public function testFormatUrl()
    {
        $formatter = new Formatter\Text();

        $url = new Url();
        $url->setLoc('http://www.google.fr');

        $this->assertEquals('http://www.google.fr' . "\n", $formatter->formatUrl($url));
    }
}
