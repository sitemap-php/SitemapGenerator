<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\MemoryDumper;

class MemoryDumperTest extends \PHPUnit_Framework_TestCase
{
    public function testDumper()
    {
        $dumper = new MemoryDumper();
        $this->assertEquals('foo', $dumper->dump('foo'));
        $this->assertEquals('foo-bar', $dumper->dump('-bar'));
    }
}
