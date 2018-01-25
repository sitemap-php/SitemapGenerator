<?php

namespace SitemapGenerator\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Entity\SitemapIndexEntry;

class SitemapIndexEntryTest extends TestCase
{
    /**
     * @expectedException \DomainException
     */
    public function testLocMaxLength()
    {
        new SitemapIndexEntry('http://google.fr/?q=' . str_repeat('o', 2048));
    }

    public function testConstructionWithASingleArgument()
    {
        $entry = new SitemapIndexEntry('http://google.fr/');

        $this->assertSame('http://google.fr/', $entry->getLoc());
        $this->assertNull($entry->getLastmod());
    }

    public function testConstructionWithAllTheArguments()
    {
        $entry = new SitemapIndexEntry('http://google.fr/', \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2016-02-28 14:34:25', new \DateTimeZone('Europe/Paris')));

        $this->assertSame('http://google.fr/', $entry->getLoc());
        $this->assertSame('2016-02-28T14:34:25+01:00', $entry->getLastmod());
    }
}
