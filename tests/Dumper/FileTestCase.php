<?php

namespace SitemapGenerator\Tests\Dumper;

abstract class FileTestCase extends \PHPUnit_Framework_TestCase
{
    private $file;
    private $otherFile;
    private $nonWriteableFile;

    public function setUp()
    {
        $this->file = sys_get_temp_dir() . '/dummy_file';
        $this->otherFile = sys_get_temp_dir() . '/other_file';
        $this->nonWriteableFile = sys_get_temp_dir() . '/non_writeable_file';

        touch($this->nonWriteableFile);
        chmod($this->nonWriteableFile, 0400);
    }

    public function tearDown()
    {
        file_exists($this->file) && unlink($this->file);
        file_exists($this->otherFile) && unlink($this->otherFile);
        file_exists($this->nonWriteableFile) && unlink($this->nonWriteableFile);
    }

    protected function nonWriteableFile(): string
    {
        return $this->nonWriteableFile;
    }

    protected function dummyFile(): string
    {
        return $this->file;
    }

    protected function otherDummyFile(): string
    {
        return $this->otherFile;
    }
}
