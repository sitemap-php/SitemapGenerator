<?php

namespace SitemapGenerator\Tests\Entity;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Entity\Video;

class VideoTest extends TestCase
{
    public function testTitleMaxLength(): void
    {
        $this->expectException(\DomainException::class);

        new Video(str_repeat('o', 100), 'Description.', 'https://thumbnail.loc/img.jpg');
        $this->assertTrue(true);

        new Video(str_repeat('o', 101), 'Description.', 'https://thumbnail.loc/img.jpg');
    }

    public function testDescriptionMaxLength(): void
    {
        $this->expectException(\DomainException::class);

        new Video('title', str_repeat('o', 2048), 'https://thumbnail.loc/img.jpg');
        $this->assertTrue(true);

        new Video('title', str_repeat('o', 2049), 'https://thumbnail.loc/img.jpg');
    }

    /**
     * @dataProvider invalidDurationProvider
     */
    public function testInvalidDuration($duration): void
    {
        $this->expectException(\DomainException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setDuration($duration);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testExpirationDate($date, $expectedDate): void
    {
        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setExpirationDate($date);
        $this->assertSame($video->getExpirationDate(), $expectedDate);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testPublicationDate($date, $expectedDate): void
    {
        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setPublicationDate($date);
        $this->assertSame($video->getPublicationDate(), $expectedDate);
    }

    /**
     * @dataProvider invalidRatingProvider
     */
    public function testInvalidRating($rating): void
    {
        $this->expectException(\DomainException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setRating($rating);
    }

    public function testInvalidViewCount(): void
    {
        $this->expectException(\DomainException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setViewCount(-1);
    }

    public function testInvalidTagsCount(): void
    {
        $this->expectException(\DomainException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setTags(array_pad([], 33, 'tag'));
    }

    public function testCategoryMaxLength(): void
    {
        $this->expectException(\DomainException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setCategory(str_repeat('o', 256));
        $this->assertTrue(true);

        $video->setCategory(str_repeat('o', 257));
    }

    public function testInvalidRestriction(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setRestrictions(['fr', 'en'], 'foo');
    }

    public function testInvalidPlatform(): void
    {
        $this->expectException(\DomainException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setPlatforms([Video::PLATFORM_TV => Video::RESTRICTION_DENY, 'foo' => Video::RESTRICTION_DENY]);
    }

    public function testInvalidPlatformRelationship(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $video = new Video('title', 'Description', 'https://thumbnail.loc/img.jpg');
        $video->setPlatforms([Video::PLATFORM_TV => Video::RESTRICTION_DENY, Video::PLATFORM_MOBILE => 'foo']);
    }

    public function invalidDurationProvider()
    {
        return [
            [-1],
            [28801],
        ];
    }

    public function invalidRatingProvider()
    {
        return [
            [-1],
            [6],
        ];
    }

    public function dateProvider()
    {
        return [
            [new \DateTime('2012-12-20'), $this->dateFormatW3C('2012-12-20')],
        ];
    }

    protected function dateFormatW3C($date)
    {
        $date = new \DateTime($date);

        return $date->format(\DateTime::W3C);
    }
}
