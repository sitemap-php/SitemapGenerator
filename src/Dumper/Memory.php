<?php

namespace SitemapGenerator\Dumper;

use SitemapGenerator\Dumper;

/**
 * Dump a sitemap in memory. Usefull if you don't want to touch your filesystem
 * or if you want to access the sitemap's content.
 */
class Memory implements Dumper
{
    protected $buffer = '';

    /**
     * {@inheritdoc}
     */
    public function dump($string)
    {
        $this->buffer .= $string;

        return $this->buffer;
    }

    public function getBuffer()
    {
        return $this->buffer;
    }
}
