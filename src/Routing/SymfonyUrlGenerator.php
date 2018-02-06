<?php

declare(strict_types=1);

namespace SitemapGenerator\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;
use SitemapGenerator\UrlGenerator;

final class SymfonyUrlGenerator implements UrlGenerator
{
    private $originalGenerator;

    public function __construct(SymfonyUrlGeneratorInterface $generator)
    {
        $this->originalGenerator = $generator;
    }

    public function generate(string $name, $parameters = []): string
    {
        return $this->originalGenerator->generate($name, $parameters);
    }
}
