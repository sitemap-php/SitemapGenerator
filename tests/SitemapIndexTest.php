<?php

namespace SitemapGenerator\Tests;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\SitemapIndex;

class SitemapIndexTest extends \PHPUnit_Framework_TestCase
{
    const BASE_HOST = 'http://www.some-host.com/';

    public function testProvidersCanBeRegistered()
    {
        $sitemap = new SitemapIndex($this->getFileDumper(), $this->getFormatter(), self::BASE_HOST);
        $sitemap->addProvider(new \ArrayIterator([]));
    }

    public function testBuild()
    {
        $dumper = $this->getFileDumper();
        $formatter = $this->getFormatter();

        $sitemap = new SitemapIndex($dumper, $formatter, self::BASE_HOST, $sitemapMaxSize = 1);
        $sitemap->addProvider(new \ArrayIterator([
            new Url('foo'),
            new Url('bar'),
            new Url('baz'),
        ]));

        $formatter->expects($this->once())->method('getSitemapIndexStart');
        $formatter->expects($this->once())->method('getSitemapIndexEnd');
        $formatter->expects($this->exactly(3))->method('formatSitemapIndex');

        $dumper->expects($this->any())->method('getFilename')->will($this->returnValue('some-file-name'));
        $dumper->expects($this->exactly(3))->method('changeFile');

        $sitemap->build();
    }

    protected function getFileDumper()
    {
        return $this->getMock('SitemapGenerator\FileDumper');
    }

    protected function getFormatter()
    {
        return $this->getMock('SitemapGenerator\SitemapIndexFormatter');
    }
}
