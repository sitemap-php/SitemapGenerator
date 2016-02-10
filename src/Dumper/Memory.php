<?php

declare(strict_types=1);

namespace SitemapGenerator\Dumper;

use SitemapGenerator\Dumper;

/**
 * Dump a sitemap in memory. Useful if you don't want to touch your filesystem
 * or if you want to access the sitemap's content.
 */
class Memory implements Dumper
{
    protected $buffer = '';

    /**
     * {@inheritdoc}
     */
    public function dump(string $string)
    {
        $this->buffer .= $string;

        return $this->buffer;
    }

    public function getBuffer(): string
    {
        return $this->buffer;
    }
}
