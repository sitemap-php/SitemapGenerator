<?php

namespace SitemapGenerator\Entity;

interface ChangeFrequency
{
    const ALWAYS = 'always';
    const HOURLY = 'hourly';
    const DAILY  = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const NEVER  = 'never';
}
