<?php

namespace SitemapGenerator\Tests\Provider;

use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Provider\DefaultValues;

class DefaultValuesTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyDefaultValuesCanBeCreated()
    {
        $values = DefaultValues::none();

        $this->assertFalse($values->hasLastmod());
        $this->assertFalse($values->hasPriority());
        $this->assertFalse($values->hasChangeFreq());

        $this->assertNull($values->getLastmod());
        $this->assertNull($values->getPriority());
        $this->assertNull($values->getChangeFreq());
    }

    public function testDefaultValuesCanBeGiven()
    {
        $priority = 0.4;
        $changeFreq = ChangeFrequency::ALWAYS;
        $lastmod = new \DateTimeImmutable();

        $values = DefaultValues::create($priority, $changeFreq, $lastmod);

        $this->assertTrue($values->hasLastmod());
        $this->assertTrue($values->hasPriority());
        $this->assertTrue($values->hasChangeFreq());

        $this->assertSame($lastmod, $values->getLastmod());
        $this->assertSame($priority, $values->getPriority());
        $this->assertSame($changeFreq, $values->getChangeFreq());
    }
}
