<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\GzFile;

class GzFileTest extends FileTestCase
{
    public function testDumper()
    {
        $dumper = new GzFile($this->file);

        $dumper->dump('joe');
        $dumper->dump('-hell yeah!');

        $this->assertTrue(file_exists($this->file));
        unset($dumper); // force the dumper to close the file

        $this->assertSame('joe-hell yeah!', file_get_contents('compress.zlib://' . $this->file));
        $this->assertNotSame('joe-hell yeah!', file_get_contents($this->file), 'The file\'s content is compressed');
    }
}
