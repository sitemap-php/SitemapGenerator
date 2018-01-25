<?php

declare(strict_types=1);

namespace SitemapGenerator\Entity;

/**
 * Represents an image in a sitemap entry.
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=fr&answer=178636
 */
class Image
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

    public function setLoc($loc)
    {
        $this->loc = $loc;
    }

    public function getLoc()
    {
        return $this->loc;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setGeoLocation($geoLocation)
    {
        $this->geoLocation = $geoLocation;
    }

    public function getGeoLocation()
    {
        return $this->geoLocation;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setLicense($license)
    {
        $this->license = $license;
    }

    public function getLicense()
    {
        return $this->license;
    }
}
