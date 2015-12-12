<?php

namespace SitemapGenerator\Tests\Dumper;

abstract class FileTestCase extends \PHPUnit_Framework_TestCase
{
    protected $file;

    public function setUp()
    {
        $this->file = sys_get_temp_dir() . '/SitemapGeneratorFileDumperTest';
    }

    public function tearDown()
    {
        unlink($this->file);
    }
}
