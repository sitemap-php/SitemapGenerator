<?php

namespace SitemapGenerator\Dumper;

/**
 * Dumps content into a file.
 */
interface FileDumper extends Dumper
{
    /**
     * Set the filename.
     *
     * @param string $filename The filename.
     */
    public function setFilename($filename);

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
}
