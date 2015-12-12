<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\Memory;

class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testDumper()
    {
        $dumper = new Memory();
        $this->assertEquals('foo', $dumper->dump('foo'));
        $this->assertEquals('foo-bar', $dumper->dump('-bar'));
    }
}
