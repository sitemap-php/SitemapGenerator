<?php

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\SitemapIndex as SitemapIndexEntity;

class Spaceless implements SitemapIndex
{
    protected $formatter;

    public function __construct(Sitemap $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getSitemapStart()
    {
        return $this->stripSpaces($this->formatter->getSitemapStart());
    }

    public function getSitemapEnd()
    {
        return $this->stripSpaces($this->formatter->getSitemapEnd());
    }

    public function formatUrl(Url $url)
    {
        return $this->stripSpaces($this->formatter->formatUrl($url));
    }

    public function getSitemapIndexStart()
    {
        if (!$this->formatter instanceof SitemapIndex) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexStart());
    }

    public function getSitemapIndexEnd()
    {
        if (!$this->formatter instanceof SitemapIndex) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexEnd());
    }

    public function formatSitemapIndex(SitemapIndexEntity $sitemapIndex)
    {
        if (!$this->formatter instanceof SitemapIndex) {
            return '';
        }

        return $this->stripSpaces($this->formatter->formatSitemapIndex($sitemapIndex));
    }

    protected function stripSpaces($string)
    {
        return str_replace(["\t", "\r", "\n"], '', $string);
    }
}
