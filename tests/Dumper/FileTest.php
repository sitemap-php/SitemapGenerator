<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\File;

class FileTest extends FileTestCase
{
    public function testDumper()
    {
        $dumper = new File($this->dummyFile());

        $dumper->dump('joe');
        $this->assertTrue(file_exists($this->dummyFile()));

        $this->assertSame('joe', file_get_contents($this->dummyFile()));

        $dumper->dump('-hell yeah!');
        $this->assertSame('joe-hell yeah!', file_get_contents($this->dummyFile()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAnExceptionIsThrownForNonWriteableFiles()
    {
        $dumper = new File($this->nonWriteableFile());
        $dumper->dump('foo');
    }

    public function testCurrentFilenameIsAccessible()
    {
        $dumper = new File($this->dummyFile());

        $this->assertSame($this->dummyFile(), $dumper->getFilename());
    }

    public function testFilenameCanBeChanged()
    {
        $dumper = new File($this->dummyFile());
        $newDumper = $dumper->changeFile($this->otherDummyFile());

        $this->assertSame($this->otherDummyFile(), $newDumper->getFilename());
        $this->assertNotSame($newDumper, $dumper);
    }
}
