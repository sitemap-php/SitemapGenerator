<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\File;

class FileTest extends \PHPUnit_Framework_TestCase
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

    public function testDumper()
    {
        $dumper = new File($this->file);

        $dumper->dump('joe');
        $this->assertTrue(file_exists($this->file));

        $this->assertEquals('joe', file_get_contents($this->file));

        $dumper->dump('-hell yeah!');
        $this->assertEquals('joe-hell yeah!', file_get_contents($this->file));
    }
}
