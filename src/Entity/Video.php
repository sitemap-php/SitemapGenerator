<?php

declare(strict_types=1);

namespace SitemapGenerator\Entity;

/**
 * Represents a video in a sitemap entry.
 *
 * @see https://developers.google.com/webmasters/videosearch/sitemaps
 */
final class Video
{
    public const RESTRICTION_DENY = 'deny';
    public const RESTRICTION_ALLOW = 'allow';

    public const PLATFORM_TV = 'tv';
    public const PLATFORM_MOBILE = 'mobile';
    public const PLATFORM_WEB = 'web';

    /*********************
     * Required attributes
     ********************/

    /**
     * A URL pointing to the video thumbnail image file. Images must be at
     * least 160x90 pixels and at most 1920x1080 pixels. We recommend images
     * in .jpg, .png, or. gif formats.
     */
    private $thumbnailLoc;

    /**
     * The title of the video. Maximum 100 characters.
     */
    private $title;

    /**
     * The description of the video. Maximum 2048 characters.
     */
    private $description;

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
    private $contentLoc;

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
    private $playerLoc;

    /**
     * The duration of the video in seconds. Value must be between 0 and
     * 28800 (8 hours).
     */
    private $duration;

    /**
     * The date after which the video will no longer be available. Don't
     * supply this information if your video does not expire.
     *
     * @var \DateTimeInterface
     */
    private $expirationDate;

    /**
     * The rating of the video. Allowed values are float numbers in the range
     * 0.0 to 5.0.
     */
    private $rating;

    /**
     * The number of times the video has been viewed.
     */
    private $viewCount;

    /**
     * The date the video was first published
     *
     * @var \DateTimeInterface
     */
    private $publicationDate;

    /**
     * No if the video should be available only to users with SafeSearch turned off.
     */
    private $familyFriendly;

    /**
     * Tags associated with the video.
     */
    private $tags = [];

    /**
     * The video's category. For example, cooking. The value should be a
     * string no longer than 256 characters.
     */
    private $category;

    /**
     * A space-delimited list of countries where the video may or may not be
     * played. Allowed values are country codes in ISO 3166 format.
     *
     * @see https://developers.google.com/webmasters/videosearch/countryrestrictions
     */
    private $restrictions;

    /**
     * A link to the gallery (collection of videos) in which this video appears.
     */
    private $galleryLoc;

    /**
     * Indicates whether a subscription (either paid or free) is required to view the video.
     */
    private $requiresSubscription;

    /**
     * The video uploader's name.
     */
    private $uploader;

    /**
     * A list of space-delimited platforms where the video may or may not be
     * played. Allowed values are web, mobile, and tv.
     *
     * @see https://developers.google.com/webmasters/videosearch/platformrestrictions
     */
    private $platforms;

    /**
     * Indicates whether the video is a live stream.
     */
    private $live;

    public function __construct(string $title, string $description, string $thumbnailLoc)
    {
        if (\strlen($title) > 100) {
            throw new \DomainException('The title value must be less than 100 characters');
        }

        if (\strlen($description) > 2048) {
            throw new \DomainException('The description value must be less than 2,048 characters');
        }

        $this->title = $title;
        $this->description = $description;
        $this->thumbnailLoc = $thumbnailLoc;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getThumbnailLoc(): string
    {
        return $this->thumbnailLoc;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setContentLoc(string $loc): void
    {
        $this->contentLoc = $loc;
    }

    public function getContentLoc(): ?string
    {
        return $this->contentLoc;
    }

    public function setPlayerLoc(string $loc, bool $allowEmbed = true, ?string $autoplay = null): void
    {
        $this->playerLoc = [
            'loc' => $loc,
            'allow_embed' => $allowEmbed,
            'autoplay' => $autoplay,
        ];
    }

    public function getPlayerLoc(): ?array
    {
        return $this->playerLoc;
    }

    public function setDuration(int $duration): void
    {
        if ($duration < 0 || $duration > 28800) {
            throw new \DomainException('The duration must be between 0 and 28800 seconds');
        }

        $this->duration = $duration;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setExpirationDate(\DateTimeInterface $date): void
    {
        $this->expirationDate = $date;
    }

    public function getExpirationDate(): ?string
    {
        if ($this->expirationDate === null) {
            return null;
        }

        return $this->expirationDate->format(\DateTime::W3C);
    }

    public function setRating(float $rating): void
    {
        if ($rating < 0 || $rating > 5) {
            throw new \DomainException('The rating must be between 0 and 5');
        }

        $this->rating = $rating;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setViewCount(int $count): void
    {
        if ($count < 0) {
            throw new \DomainException('The view count must be positive');
        }

        $this->viewCount = $count;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setPublicationDate(\DateTimeInterface $date): void
    {
        $this->publicationDate = $date;
    }

    public function getPublicationDate(): ?string
    {
        if ($this->publicationDate === null) {
            return null;
        }

        return $this->publicationDate->format(\DateTime::W3C);
    }

    public function setFamilyFriendly(bool $friendly): void
    {
        $this->familyFriendly = $friendly;
    }

    public function getFamilyFriendly(): ?bool
    {
        return $this->familyFriendly;
    }

    public function setTags(array $tags): void
    {
        if (\count($tags) > 32) {
            throw new \DomainException('A maximum of 32 tags is allowed.');
        }

        $this->tags = $tags;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setCategory(string $category): void
    {
        if (\strlen($category) > 256) {
            throw new \DomainException('The category value must be less than 256 characters');
        }

        $this->category = $category;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setRestrictions(array $restrictions, string $relationship = self::RESTRICTION_DENY): void
    {
        if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
            throw new \InvalidArgumentException('The relationship must be deny or allow');
        }

        $this->restrictions = [
            'countries' => $restrictions,
            'relationship' => $relationship,
        ];
    }

    public function getRestrictions(): ?array
    {
        return $this->restrictions;
    }

    public function setGalleryLoc(string $loc, ?string $title = null): void
    {
        $this->galleryLoc = [
            'loc' => $loc,
            'title' => $title,
        ];
    }

    public function getGalleryLoc(): ?array
    {
        return $this->galleryLoc;
    }

    public function setRequiresSubscription(bool $requiresSubscription): void
    {
        $this->requiresSubscription = $requiresSubscription;
    }

    public function getRequiresSubscription(): ?bool
    {
        return $this->requiresSubscription;
    }

    public function setUploader(string $uploader, ?string $info = null): void
    {
        $this->uploader = [
            'name' => $uploader,
            'info' => $info,
        ];
    }

    public function getUploader(): ?array
    {
        return $this->uploader;
    }

    public function setPlatforms(array $platforms): void
    {
        $valid_platforms = [self::PLATFORM_TV, self::PLATFORM_WEB, self::PLATFORM_MOBILE];
        foreach ($platforms as $platform => $relationship) {
            if (!\in_array($platform, $valid_platforms, true)) {
                throw new \DomainException(sprintf('Invalid platform given. Valid values are: %s', implode(', ', $valid_platforms)));
            }

            if ($relationship !== self::RESTRICTION_ALLOW && $relationship !== self::RESTRICTION_DENY) {
                throw new \InvalidArgumentException('The relationship must be deny or allow');
            }
        }

        $this->platforms = $platforms;
    }

    public function getPlatforms(): ?array
    {
        return $this->platforms;
    }

    public function setLive(bool $live): void
    {
        $this->live = $live;
    }

    public function getLive(): ?bool
    {
        return $this->live;
    }
}
