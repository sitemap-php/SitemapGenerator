<?php

declare(strict_types=1);

namespace SitemapGenerator\Provider;

class DefaultValues
{
    private $priority;
    private $changeFreq;
    private $lastmod;

    final private function __construct()
    {
    }

    public static function create(float $priority, string $changeFreq, \DateTimeInterface $lastmod = null): DefaultValues
    {
        $defaultValues = static::none();

        $defaultValues->priority = $priority;
        $defaultValues->changeFreq = $changeFreq;
        $defaultValues->lastmod = $lastmod;

        return $defaultValues;
    }

    public static function none(): DefaultValues
    {
        return new static();
    }

    public function hasLastmod(): bool
    {
        return $this->lastmod !== null;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function hasPriority(): bool
    {
        return $this->priority !== null;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function hasChangeFreq(): bool
    {
        return $this->changeFreq !== null;
    }

    public function getChangeFreq()
    {
        return $this->changeFreq;
    }
}
