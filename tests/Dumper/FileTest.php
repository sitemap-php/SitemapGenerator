<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\File;

class FileTest extends FileTestCase
{
    protected function createDumper()
    {
        return new File($this->dummyFile());
    }

    public function testDumper()
    {
        $this->dumper->dump('joe');
        $this->assertTrue(file_exists($this->dummyFile()));

        $this->assertSame('joe', file_get_contents($this->dummyFile()));

        $this->dumper->dump('-hell yeah!');
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
        $this->assertSame($this->dummyFile(), $this->dumper->getFilename());
    }

    public function testFilenameCanBeChanged()
    {
        $newDumper = $this->dumper->changeFile($this->otherDummyFile());

        $this->assertSame($this->otherDummyFile(), $newDumper->getFilename());
        $this->assertNotSame($newDumper, $this->dumper);
    }
}
