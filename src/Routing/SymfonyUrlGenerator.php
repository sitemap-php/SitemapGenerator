<?php

namespace SitemapGenerator\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;
use SitemapGenerator\UrlGenerator;

class SymfonyUrlGenerator implements UrlGenerator
{
    private $originalGenerator;

    public function __construct(SymfonyUrlGeneratorInterface $generator)
    {
        $this->originalGenerator = $generator;
    }

    public function generate($name, $parameters = [])
    {
        return $this->originalGenerator->generate($name, $parameters);
    }
}
