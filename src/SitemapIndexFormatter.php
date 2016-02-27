<?php

declare(strict_types=1);

namespace SitemapGenerator;

use SitemapGenerator\Entity;

interface SitemapIndexFormatter extends SitemapFormatter
{
    public function getSitemapIndexStart(): string;
    public function getSitemapIndexEnd(): string;
    public function formatSitemapIndex(Entity\SitemapIndexEntry $entry): string;
}
