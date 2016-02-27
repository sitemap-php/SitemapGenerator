<?php

namespace SitemapGenerator\Tests\Dumper;

use SitemapGenerator\Dumper\Memory;

class MemoryTest extends \PHPUnit_Framework_TestCase
{
    public function testDumper()
    {
        $dumper = new Memory();
        $this->assertSame('foo', $dumper->dump('foo'));
        $this->assertSame('foo', $dumper->getBuffer());
        $this->assertSame('foo-bar', $dumper->dump('-bar'));
        $this->assertSame('foo-bar', $dumper->getBuffer());
    }
}
