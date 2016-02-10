<?php

declare(strict_types=1);

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity;
use SitemapGenerator\SitemapFormatter;
use SitemapGenerator\SitemapIndexFormatter;

class Spaceless implements SitemapIndexFormatter
{
    protected $formatter;

    public function __construct(SitemapFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getSitemapStart(): string
    {
        return $this->stripSpaces($this->formatter->getSitemapStart());
    }

    public function getSitemapEnd(): string
    {
        return $this->stripSpaces($this->formatter->getSitemapEnd());
    }

    public function formatUrl(Entity\Url $url): string
    {
        return $this->stripSpaces($this->formatter->formatUrl($url));
    }

    public function getSitemapIndexStart(): string
    {
        if (!$this->formatter instanceof Entity\SitemapIndex) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexStart());
    }

    public function getSitemapIndexEnd(): string
    {
        if (!$this->formatter instanceof SitemapIndexFormatter) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexEnd());
    }

    public function formatSitemapIndex(Entity\SitemapIndex $sitemapIndex): string
    {
        if (!$this->formatter instanceof SitemapIndexFormatter) {
            return '';
        }

        return $this->stripSpaces($this->formatter->formatSitemapIndex($sitemapIndex));
    }

    protected function stripSpaces($string): string
    {
        return str_replace(["\t", "\r", "\n"], '', $string);
    }
}
