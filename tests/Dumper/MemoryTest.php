<?php

namespace SitemapGenerator\Tests\Dumper;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Dumper\Memory;

class MemoryTest extends TestCase
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
