<?php

namespace SitemapGenerator\Tests\Formatter;

use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Entity\RichUrl;
use SitemapGenerator\Formatter;

class RichXmlFormatterTest extends XmlFormatterTest
{
    protected function setUp()
    {
        $this->formatter = new Formatter\RichXml();
    }

    public function testSitemapStart()
    {
        $this->assertSame('<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n", $this->formatter->getSitemapStart());
    }

    public function testFormatRichUrl()
    {
        $url = new RichUrl('http://www.google.fr');
        $url->setPriority(0.2);
        $url->setChangeFreq(ChangeFrequency::NEVER);
        $url->addAlternateUrl('en', 'http://www.google.com');
        $url->addAlternateUrl('es-es', 'http://www.google.es');

        $this->assertSame("<url>\n" .
"\t<loc>http://www.google.fr</loc>\n" .
"\t<changefreq>never</changefreq>\n" .
"\t<priority>0.2</priority>\n" .
"\t<xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"http://www.google.com\" />\n" .
"\t<xhtml:link rel=\"alternate\" hreflang=\"es-es\" href=\"http://www.google.es\" />\n" .
"</url>\n", $this->formatter->formatUrl($url));
    }
}
