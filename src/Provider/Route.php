<?php

declare(strict_types=1);

namespace SitemapGenerator\Provider;

use SitemapGenerator\Entity;
use SitemapGenerator\UrlGenerator;

class Route implements \IteratorAggregate
{
    /**
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var DefaultValues
     */
    private $defaultValues;

    public function __construct(UrlGenerator $urlGenerator, array $routes, DefaultValues $defaultValues = null)
    {
        $this->urlGenerator = $urlGenerator;
        $this->routes = $routes;
        $this->defaultValues = $defaultValues ?: DefaultValues::none();
    }

    public function getIterator()
    {
        $defaultRouteData = [
            'changefreq' => null,
            'lastmod' => null,
            'priority' => null,
        ];

        foreach ($this->routes as $route) {
            $route = array_merge($defaultRouteData, $route);

            $url = new Entity\Url(
                $this->urlGenerator->generate($route['name'], $route['params'])
            );

            $url->setChangeFreq($route['changefreq'] ?: $this->defaultValues->getChangeFreq());
            $url->setLastmod($route['lastmod'] ?: $this->defaultValues->getLastmod());
            $url->setPriority($route['priority'] ?: $this->defaultValues->getPriority());

            yield $url;
        }
    }
}
