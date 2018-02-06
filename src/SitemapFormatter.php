<?php

declare(strict_types=1);

namespace SitemapGenerator;

interface SitemapFormatter
{
    public function getSitemapStart(): string;

    public function getSitemapEnd(): string;

    public function formatUrl(Entity\Url $url): string;
}
