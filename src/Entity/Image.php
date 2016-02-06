<?php

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
    protected $loc;

    /**
     * The caption of the image.
     */
    protected $caption;

    /**
     * The geographic location of the image.
     */
    protected $geoLocation;

    /**
     * The title of the image.
     */
    protected $title;

    /**
     * A URL to the license of the image.
     */
    protected $license;

    public function setLoc($loc)
    {
        $this->loc = $loc;

        return $this;
    }

    public function getLoc()
    {
        return $this->loc;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setGeoLocation($geoLocation)
    {
        $this->geoLocation = $geoLocation;

        return $this;
    }

    public function getGeoLocation()
    {
        return $this->geoLocation;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    public function getLicense()
    {
        return $this->license;
    }
}
