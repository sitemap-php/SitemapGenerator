<?php

declare(strict_types=1);

namespace SitemapGenerator\Entity;

final class ChangeFrequency
{
    public const ALWAYS = 'always';
    public const HOURLY = 'hourly';
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';
    public const MONTHLY = 'monthly';
    public const YEARLY = 'yearly';
    public const NEVER = 'never';

    public const KNOWN_FREQUENCIES = [
        self::ALWAYS, self::HOURLY, self::DAILY,
        self::WEEKLY, self::MONTHLY, self::YEARLY,
        self::NEVER,
    ];

    public static function isValid(string $changeFrequency): bool
    {
        return \in_array($changeFrequency, self::KNOWN_FREQUENCIES, true);
    }
}
