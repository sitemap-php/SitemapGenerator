<?php

namespace SitemapGenerator\Provider;

class DefaultValues
{
    private $priority;
    private $changeFreq;
    private $lastmod;

    private function __construct()
    {
    }

    public static function create($priority, $changeFreq, \DateTimeInterface $lastmod = null)
    {
        $defaultValues = static::none();

        $defaultValues->priority = $priority;
        $defaultValues->changeFreq = $changeFreq;
        $defaultValues->lastmod = $lastmod;

        return $defaultValues;
    }

    public static function none()
    {
        return new static();
    }

    public function hasLastmod()
    {
        return $this->lastmod !== null;
    }

    public function getLastmod()
    {
        return $this->lastmod;
    }

    public function hasPriority()
    {
        return $this->priority !== null;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function hasChangeFreq()
    {
        return $this->changeFreq !== null;
    }

    public function getChangefreq()
    {
        return $this->changeFreq;
    }
}
