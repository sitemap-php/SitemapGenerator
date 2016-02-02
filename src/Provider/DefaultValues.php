<?php

namespace SitemapGenerator\Provider;

class DefaultValues
{
    private $priority;
    private $changeFreq;

    private function __construct()
    {
    }

    public static function create($priority, $changeFreq)
    {
        $defaultValues = static::empty();

        $defaultValues->priority = $priority;
        $defaultValues->changeFreq = $changeFreq;

        return $defaultValues;
    }

    public static function empty()
    {
        return new static();
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
