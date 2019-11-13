<?php

namespace SitemapGenerator\Tests\Routing;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Routing\SymfonyUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SymfonyUrlGeneratorTest extends TestCase
{
    public function testGenerateDelegatesToTheUrlGenerator(): void
    {
        $route = 'route_name';
        $routeParameters = ['foo' => 'bar'];

        $generatorMock = $this->createMock(UrlGeneratorInterface::class);
        $generatorMock
            ->method('generate')
            ->with($route, $routeParameters)
            ->willReturn('/some/url');

        $urlGenerator = new SymfonyUrlGenerator($generatorMock);

        $this->assertSame('/some/url', $urlGenerator->generate($route, $routeParameters));
    }
}
