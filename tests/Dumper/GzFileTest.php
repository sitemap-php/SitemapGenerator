<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\GzFile;

class GzFileTest extends FileTestCase
{
    protected function createDumper()
    {
        return new GzFile($this->dummyFile());
    }

    public function testDumper()
    {
        $this->dumper->dump('joe');
        $this->dumper->dump('-hell yeah!');

        $this->assertTrue(file_exists($this->dummyFile()));
        unset($this->dumper); // force the dumper to close the file

        $this->assertSame('joe-hell yeah!', file_get_contents('compress.zlib://' . $this->dummyFile()));
        $this->assertNotSame('joe-hell yeah!', file_get_contents($this->dummyFile()), 'The file\'s content is compressed');
    }
}
