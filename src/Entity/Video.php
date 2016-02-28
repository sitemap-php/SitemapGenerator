<?php

declare(strict_types=1);

namespace SitemapGenerator\Entity;

/**
 * Represents a video in a sitemap entry.
 *
 * @see https://developers.google.com/webmasters/videosearch/sitemaps
 */
class Video
{
    const RESTRICTION_DENY = 'deny';
    const RESTRICTION_ALLOW = 'allow';

    const PLATFORM_TV = 'tv';
    const PLATFORM_MOBILE = 'mobile';
    const PLATFORM_WEB = 'web';

    /*********************
     * Required attributes
     ********************/

    /**
     * A URL pointing to the video thumbnail image file. Images must be at
     * least 160x90 pixels and at most 1920x1080 pixels. We recommend images
     * in .jpg, .png, or. gif formats.
     */
    protected $thumbnailLoc;

    /**
     * The title of the video. Maximum 100 characters.
     */
    protected $title;

    /**
     * The description of the video. Maximum 2048 characters.
     */
    protected $description;

    /*********************
     * Optional attributes
     *********************/

    /**
     * You must specify at least one of playerLoc or contentLoc attributes.
     *
     * A URL pointing to the actual video media file. This file should be in
     * .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv,
     * or other video file format.
     */
    protected $contentLoc;

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
    protected $playerLoc;

    /**
     * The duration of the video in seconds. Value must be between 0 and
     * 28800 (8 hours).
     */
    protected $duration;

    /**
     * The date after which the video will no longer be available. Don't
     * supply this information if your video does not expire.
     *
     * @var \DateTimeInterface
     */
    protected $expirationDate;

    /**
     * The rating of the video. Allowed values are float numbers in the range
     * 0.0 to 5.0.
     */
    protected $rating;

    /**
     * The number of times the video has been viewed.
     */
    protected $viewCount;

    /**
     * The date the video was first published
     *
     * @var \DateTimeInterface
     */
    protected $publicationDate;

    /**
     * No if the video should be available only to users with SafeSearch turned off.
     */
    protected $familyFriendly;

    /**
     * Tags associated with the video.
     */
    protected $tags = [];

    /**
     * The video's category. For example, cooking. The value should be a
     * string no longer than 256 characters.
     */
    protected $category;

    /**
     * A space-delimited list of countries where the video may or may not be
     * played. Allowed values are country codes in ISO 3166 format.
     *
     * @see https://developers.google.com/webmasters/videosearch/countryrestrictions
     */
    protected $restrictions;

    /**
     * A link to the gallery (collection of videos) in which this video appears.
     */
    protected $galleryLoc;

    /**
     * Indicates whether a subscription (either paid or free) is required to view the video.
     */
    protected $requiresSubscription;

    /**
     * The video uploader's name.
     */
    protected $uploader;

    /**
     * A list of space-delimited platforms where the video may or may not be
     * played. Allowed values are web, mobile, and tv.
     *
     * @see https://developers.google.com/webmasters/videosearch/platformrestrictions
     */
    protected $platforms;

    /**
     * Indicates whether the video is a live stream.
     */
    protected $live;


    public function setTitle($title)
    {
        if (strlen($title) > 100) {
            throw new \DomainException('The title value must be less than 100 characters');
        }

        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setThumbnailLoc($loc)
    {
        $this->thumbnailLoc = $loc;
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
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setContentLoc($loc)
    {
        $this->contentLoc = $loc;
    }

    public function getContentLoc()
    {
        return $this->contentLoc;
    }

    public function setPlayerLoc($loc, $allowEmbed = true, $autoplay = null)
    {
        if ($loc === null) {
            $this->playerLoc = null;
            return;
        }

        $this->playerLoc = [
            'loc'           => $loc,
            'allow_embed'   => $allowEmbed,
            'autoplay'      => $autoplay,
        ];
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
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setExpirationDate(\DateTimeInterface $date = null)
    {
        $this->expirationDate = $date;
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
    }

    public function getViewCount()
    {
        return $this->viewCount;
    }

    public function setPublicationDate(\DateTimeInterface $date = null)
    {
        $this->publicationDate = $date;
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
    }

    public function getFamilyFriendly()
    {
        return $this->familyFriendly;
    }

    public function setTags(array $tags)
    {
        if (count($tags) > 32) {
            throw new \DomainException('A maximum of 32 tags is allowed.');
        }

        $this->tags = $tags;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setCategory($category)
    {
        if (strlen($category) > 256) {
            throw new \DomainException('The category value must be less than 256 characters');
        }

        $this->category = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setRestrictions($restrictions, $relationship = self::RESTRICTION_DENY)
    {
        if ($restrictions === null) {
            $this->restrictions = null;
            return;
        }

        if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
            throw new \InvalidArgumentException('The relationship must be deny or allow');
        }

        $this->restrictions = [
            'countries'     => $restrictions,
            'relationship'  => $relationship,
        ];
    }

    public function getRestrictions()
    {
        return $this->restrictions;
    }

    public function setGalleryLoc($loc, $title = null)
    {
        if ($loc === null) {
            $this->galleryLoc = null;
            return;
        }

        $this->galleryLoc = [
            'loc'   => $loc,
            'title' => $title,
        ];
    }

    public function getGalleryLoc()
    {
        return $this->galleryLoc;
    }

    public function setRequiresSubscription($requiresSubscription)
    {
        $this->requiresSubscription = (bool) $requiresSubscription;
    }

    public function getRequiresSubscription()
    {
        return $this->requiresSubscription;
    }

    public function setUploader($uploader, $info = null)
    {
        if ($uploader === null) {
            $this->uploader = null;
            return;
        }

        $this->uploader = [
            'name' => $uploader,
            'info' => $info,
        ];
    }

    public function getUploader()
    {
        return $this->uploader;
    }

    public function setPlatforms(array $platforms)
    {
        if ($platforms === null) {
            $this->platforms = null;
            return;
        }

        $valid_platforms = [self::PLATFORM_TV, self::PLATFORM_WEB, self::PLATFORM_MOBILE];
        foreach ($platforms as $platform => $relationship) {
            if (!in_array($platform, $valid_platforms, true)) {
                throw new \DomainException(sprintf('Invalid platform given. Valid values are: %s', implode(', ', $valid_platforms)));
            }

            if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
                throw new \InvalidArgumentException('The relationship must be deny or allow');
            }
        }

        $this->platforms = $platforms;
    }

    public function getPlatforms()
    {
        return $this->platforms;
    }

    public function setLive($live)
    {
        $this->live = (bool) $live;
    }

    public function getLive()
    {
        return $this->live;
    }
}
