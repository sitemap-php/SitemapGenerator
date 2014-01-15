<?php

namespace SitemapGenerator\Entity;

/**
 * Represents a video in a sitemap entry.
 *
 * @see https://developers.google.com/webmasters/videosearch/sitemaps
 */
class Video
{
    const RESTRICTION_DENY  = 'deny';
    const RESTRICTION_ALLOW = 'allow';

    const PLATFORM_TV       = 'tv';
    const PLATFORM_MOBILE   = 'mobile';
    const PLATFORM_WEB      = 'web';

    /*********************
     * Required attributes
     ********************/

    /**
     * A URL pointing to the video thumbnail image file. Images must be at
     * least 160x90 pixels and at most 1920x1080 pixels. We recommend images
     * in .jpg, .png, or. gif formats.
     */
    protected $thumbnailLoc = null;

    /**
     * The title of the video. Maximum 100 characters.
     */
    protected $title = null;

    /**
     * The description of the video. Maximum 2048 characters.
     */
    protected $description = null;

    /**********************
     * Optionnal attributes
     *********************/

    /**
     * You must specify at least one of playerLoc or contentLoc attributes.
     *
     * A URL pointing to the actual video media file. This file should be in
     * .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv,
     * or other video file format.
     */
    protected $contentLoc = null;

    /**
     * You must specify at least one of playerLoc or contentLoc.
     *
     * A URL pointing to a player for a specific video. Usually this is the
     * information in the src element of an <embed> tag and should not be the
     * same as the content of the <loc> tag.
     *
     * The optional attribute allow_embed specifies whether Google can embed
     * the video in search results. Allowed values are Yes or No.
     *
     * The optional attribute autoplay has a user-defined string (in the
     * example above, ap=1) that Google may append (if appropriate) to the
     * flashvars parameter to enable autoplay of the video.
     * For example: <embed src="http://www.example.com/videoplayer.swf?video=123" autoplay="ap=1"/>.
     *
     * Example player URL for Dailymotion: http://www.dailymotion.com/swf/x1o2g
     */
    protected $playerLoc = null;

    /**
     * The duration of the video in seconds. Value must be between 0 and
     * 28800 (8 hours).
     */
    protected $duration = null;

    /**
     * The date after which the video will no longer be available. Don't
     * supply this information if your video does not expire.
     */
    protected $expirationDate = null;

    /**
     * The rating of the video. Allowed values are float numbers in the range
     * 0.0 to 5.0.
     */
    protected $rating = null;

    /**
     * The number of times the video has been viewed.
     */
    protected $viewCount = null;

    /**
     * The date the video was first published
     */
    protected $publicationDate = null;

    /**
     * No if the video should be available only to users with SafeSearch turned off.
     */
    protected $familyFriendly = null;

    /**
     * Tags associated with the video.
     */
    protected $tags = array();

    /**
     * The video's category. For example, cooking. The value should be a
     * string no longer than 256 characters.
     */
    protected $category = null;

    /**
     * A space-delimited list of countries where the video may or may not be
     * played. Allowed values are country codes in ISO 3166 format.
     *
     * @see https://developers.google.com/webmasters/videosearch/countryrestrictions
     */
    protected $restrictions = null;

    /**
     * A link to the gallery (collection of videos) in which this video appears.
     */
    protected $galleryLoc = null;

    /**
     * Indicates whether a subscription (either paid or free) is required to view the video.
     */
    protected $requiresSubscription = null;

    /**
     * The video uploader's name.
     */
    protected $uploader = null;

    /**
     * A list of space-delimited platforms where the video may or may not be
     * played. Allowed values are web, mobile, and tv.
     *
     * @see https://developers.google.com/webmasters/videosearch/platformrestrictions
     */
    protected $platforms = null;

    /**
     * Indicates whether the video is a live stream.
     */
    protected $live = null;


    public function setTitle($title)
    {
        if (strlen($title) > 100) {
            throw new \DomainException('The title value must be less than 100 characters');
        }

        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setThumbnailLoc($loc)
    {
        $this->thumbnailLoc = $loc;

        return $this;
    }

    public function getThumbnailLoc()
    {
        return $this->thumbnailLoc;
    }

    public function setDescription($description)
    {
        if (strlen($description) > 2048) {
            throw new \DomainException('The description value must be less than 2,048 characters');
        }

        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setContentLoc($loc)
    {
        $this->contentLoc = $loc;

        return $this;
    }

    public function getContentLoc()
    {
        return $this->contentLoc;
    }

    public function setPlayerLoc($loc, $allowEmbed = true, $autoplay = null)
    {
        if ($loc === null) {
            $this->playerLoc = null;

            return $this;
        }

        $this->playerLoc = array(
            'loc'           => $loc,
            'allow_embed'   => $allowEmbed,
            'autoplay'      => $autoplay !== null ? $autoplay : null,
        );

        return $this;
    }

    public function getPlayerLoc()
    {
        return $this->playerLoc;
    }

    public function setDuration($duration)
    {
        $duration = (int) $duration;

        if ($duration < 0 || $duration > 28800) {
            throw new \DomainException('The duration must be between 0 and 28800 seconds');
        }

        $this->duration = $duration;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setExpirationDate($date)
    {
        if ($date !== null && !$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }

        $this->expirationDate = $date;

        return $this;
    }

    public function getExpirationDate()
    {
        if ($this->expirationDate === null) {
            return null;
        }

        return $this->expirationDate->format(\DateTime::W3C);
    }

    public function setRating($rating)
    {
        $rating = (float) $rating;

        if ($rating < 0 || $rating > 5) {
            throw new \DomainException('The rating must be between 0 and 5');
        }

        $this->rating = $rating;

        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setViewCount($count)
    {
        $count = (int) $count;

        if ($count < 0) {
            throw new \DomainException('The view count must be positive');
        }

        $this->viewCount = $count;

        return $this;
    }

    public function getViewCount()
    {
        return $this->viewCount;
    }

    public function setPublicationDate($date)
    {
        if ($date !== null && !$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }

        $this->publicationDate = $date;

        return $this;
    }

    public function getPublicationDate()
    {
        if ($this->publicationDate === null) {
            return null;
        }

        return $this->publicationDate->format(\DateTime::W3C);
    }

    public function setFamilyFriendly($friendly)
    {
        $this->familyFriendly = (bool) $friendly;

        return $this;
    }

    public function getFamilyFriendly()
    {
        return $this->familyFriendly;
    }

    public function setTags($tags)
    {
        if ($tags === null) {
            $this->tags = null;

            return $this;
        }

        if (count($tags) > 32) {
            throw new \DomainException('A maximum of 32 tags is allowed.');
        }

        $this->tags = $tags;

        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setCategory($category)
    {
        if (strlen($category) > 256) {
            throw new \DomainException('The category value must be less than 256 characters');
        }

        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setRestrictions($restrictions, $relationship = self::RESTRICTION_DENY)
    {
        if ($restrictions === null) {
            $this->restrictions = null;

            return $this;
        }

        if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
            throw new \InvalidArgumentException('The relationship must be deny or allow');
        }

        $this->restrictions = array(
            'countries'     => $restrictions,
            'relationship'  => $relationship,
        );

        return $this;
    }

    public function getRestrictions()
    {
        return $this->restrictions;
    }

    public function setGalleryLoc($loc, $title = null)
    {
        if ($loc === null) {
            $this->galleryLoc = null;

            return $this;
        }

        $this->galleryLoc = array(
            'loc'   => $loc,
            'title' => $title
        );

        return $this;
    }

    public function getGalleryLoc()
    {
        return $this->galleryLoc;
    }

    public function setRequiresSubscription($requiresSubscription)
    {
        $this->requiresSubscription = (bool) $requiresSubscription;

        return $this;
    }

    public function getRequiresSubscription()
    {
        return $this->requiresSubscription;
    }

    public function setUploader($uploader, $info = null)
    {
        if ($uploader === null) {
            $this->uploader = null;

            return $this;
        }

        $this->uploader = array(
            'name' => $uploader,
            'info' => $info,
        );

        return $this;
    }

    public function getUploader()
    {
        return $this->uploader;
    }

    public function setPlatforms($platforms)
    {
        if ($platforms === null) {
            $this->platforms = null;

            return $this;
        }

        $valid_platforms = array(self::PLATFORM_TV, self::PLATFORM_WEB, self::PLATFORM_MOBILE);
        foreach ($platforms as $platform => $relationship) {
            if (!in_array($platform, $valid_platforms)) {
                throw new \DomainException(sprintf('Invalid platform given. Valid values are: %s', implode(', ', $valid_platforms)));
            }

            if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
                throw new \InvalidArgumentException('The relationship must be deny or allow');
            }
        }

        $this->platforms = $platforms;

        return $this;
    }

    public function getPlatforms()
    {
        return $this->platforms;
    }

    public function setLive($live)
    {
        $this->live = (bool) $live;

        return $this;
    }

    public function getLive()
    {
        return $this->live;
    }
}
