<?php

namespace SitemapGenerator\Entity;

class ChangeFrequency
{
    const ALWAYS = 'always';
    const HOURLY = 'hourly';
    const DAILY  = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const NEVER  = 'never';

    const KNOWN_FREQUENCIES = [
        self::ALWAYS, self::HOURLY, self::DAILY,
        self::WEEKLY, self::MONTHLY, self::YEARLY,
        self::NEVER,
    ];

    public static function isValid($changeFrequency)
    {
        return in_array($changeFrequency, self::KNOWN_FREQUENCIES, true);
    }
}
