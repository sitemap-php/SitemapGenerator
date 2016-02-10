<?php

declare(strict_types=1);

namespace SitemapGenerator;

/**
 * Dumps content into a file.
 */
interface FileDumper extends Dumper
{
    public function getFilename(): string;

    /**
     * Returns a new dumper, exactly like the current but which dumps content
     * in the given file.
     *
     * @param string $filename The new file to dump content to.
     */
    public function changeFile(string $filename): FileDumper;
}
