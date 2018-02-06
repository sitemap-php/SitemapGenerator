<?php

declare(strict_types=1);

namespace SitemapGenerator\Dumper;

use SitemapGenerator\FileDumper;

/**
 * Dump the sitemap into a file.
 *
 * @see GzFile
 */
class File implements FileDumper
{
    private $filename;
    private $handle;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function changeFile(string $filename): FileDumper
    {
        return new static($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * {@inheritdoc}
     */
    public function dump(string $string): void
    {
        if ($this->handle === null) {
            $this->openFile();
        }

        fwrite($this->handle, $string);
    }

    private function openFile(): void
    {
        $this->handle = @fopen($this->filename, 'w');

        if ($this->handle === false) {
            $this->handle = null;
            throw new \RuntimeException(sprintf('Impossible to open the file %s in write mode', $this->filename));
        }
    }

    /**
     * {@inheritdoc}
     */
    private function clearHandle(): void
    {
        if ($this->handle !== null) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    public function __destruct()
    {
        $this->clearHandle();
    }
}
