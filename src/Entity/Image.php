<?php

declare(strict_types=1);

namespace SitemapGenerator\Entity;

/**
 * Represents an image in a sitemap entry.
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=fr&answer=178636
 */
final class Image
{
    /**
     * The URL of the image.
     * This attribute is required.
     */
    private $loc;

    /**
     * The caption of the image.
     */
    private $caption;

    /**
     * The geographic location of the image.
     */
    private $geoLocation;

    /**
     * The title of the image.
     */
    private $title;

    /**
     * A URL to the license of the image.
     */
    private $license;

    public function __construct(string $loc)
    {
        $this->loc = $loc;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setCaption(?string $caption): void
    {
        $this->caption = $caption;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setGeoLocation(?string $geoLocation): void
    {
        $this->geoLocation = $geoLocation;
    }

    public function getGeoLocation(): ?string
    {
        return $this->geoLocation;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setLicense(?string $license): void
    {
        $this->license = $license;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }
}
