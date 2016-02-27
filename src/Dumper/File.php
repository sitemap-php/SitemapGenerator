<?php

declare(strict_types=1);

namespace SitemapGenerator\Dumper;

use SitemapGenerator\FileDumper;

/**
 * Dump the sitemap into a file.
 *
 * @see SitemapGenerator\Dumper\GzFile
 */
class File implements FileDumper
{
    protected $filename;
    protected $handle;

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
    public function dump(string $string)
    {
        if ($this->handle === null) {
            $this->openFile();
        }

        fwrite($this->handle, $string);
    }

    protected function openFile()
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
    private function clearHandle()
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
