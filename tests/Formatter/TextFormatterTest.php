<?php

namespace SitemapGenerator\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;

class TextFormatterTest extends TestCase
{
    public function testSitemapStart()
    {
        $formatter = new Formatter\Text();
        $this->assertSame('', $formatter->getSitemapStart());
    }

    public function testSitemapEnd()
    {
        $formatter = new Formatter\Text();
        $this->assertSame('', $formatter->getSitemapEnd());
    }

    public function testFormatUrl()
    {
        $formatter = new Formatter\Text();

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr' . "\n", $formatter->formatUrl($url));
    }
}
