<?php

namespace SitemapGenerator\Formatter;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Entity\SitemapIndex;
use SitemapGenerator\SitemapFormatter;
use SitemapGenerator\SitemapIndexFormatter;

class Spaceless implements SitemapIndexFormatter
{
    protected $formatter;

    public function __construct(SitemapFormatter $formatter)
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
        if (!$this->formatter instanceof SitemapIndexFormatter) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexEnd());
    }

    public function formatSitemapIndex(SitemapIndex $sitemapIndex)
    {
        if (!$this->formatter instanceof SitemapIndexFormatter) {
            return '';
        }

        return $this->stripSpaces($this->formatter->formatSitemapIndex($sitemapIndex));
    }

    protected function stripSpaces($string)
    {
        return str_replace(["\t", "\r", "\n"], '', $string);
    }
}
