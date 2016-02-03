<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Entity\Url;

class Route extends AbstractProvider
{
    protected $options = [
        'routes' => [],
        'lastmod' => null,
    ];

    protected $defaultRoute = [
        'params' => [],
        'priority' => null,
        'changefreq' => null,
        'lastmod' => null,
    ];

    public function getEntries()
    {
        foreach ($this->options['routes'] as $route) {
            $route = array_merge($this->defaultRoute, $route);

            $url = new Url();
            $url->setLoc(
                $this->urlGenerator->generate($route['name'], $route['params'])
            );
            $url->setChangefreq($route['changefreq']);
            $url->setLastmod($route['lastmod']);
            $url->setPriority($route['priority']);

            yield $url;
        }
    }
}
