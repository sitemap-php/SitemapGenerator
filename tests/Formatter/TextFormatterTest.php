<?php

namespace SitemapGenerator\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;

class TextFormatterTest extends TestCase
{
    public function testSitemapStart(): void
    {
        $formatter = new Formatter\Text();
        $this->assertSame('', $formatter->getSitemapStart());
    }

    public function testSitemapEnd(): void
    {
        $formatter = new Formatter\Text();
        $this->assertSame('', $formatter->getSitemapEnd());
    }

    public function testFormatUrl(): void
    {
        $formatter = new Formatter\Text();

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr' . "\n", $formatter->formatUrl($url));
    }
}
