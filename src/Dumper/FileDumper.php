<?php

namespace SitemapGenerator\Dumper;

use SitemapGenerator\Dumper;

/**
 * Dumps content into a file.
 */
interface FileDumper extends Dumper
{
    /**
     * Get the filename.
     *
     * @return string
     */
    public function getFilename();

    /**
     * Clear the file handle.
     */
    public function clearHandle();

    public function changeFile($filename);
}
