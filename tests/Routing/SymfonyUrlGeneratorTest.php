<?php

namespace SitemapGenerator\Tests\Routing;

use SitemapGenerator\Routing\SymfonyUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SymfonyUrlGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateDelegatesToTheUrlGenerator()
    {
        $route = 'route_name';
        $routeParameters = ['foo' => 'bar'];

        $generatorMock = $this->createMock(UrlGeneratorInterface::class);
        $generatorMock
            ->expects($this->once())
            ->method('generate')
            ->with($route, $routeParameters)
            ->will($this->returnValue('/some/url'));

        $urlGenerator = new SymfonyUrlGenerator($generatorMock);

        $this->assertSame('/some/url', $urlGenerator->generate($route, $routeParameters));
    }
}
