<?php

namespace SitemapGenerator\Provider;

use Symfony\Component\PropertyAccess\PropertyAccess;
use SitemapGenerator\Routing\UrlGenerator;

use SitemapGenerator\Entity\Url;

/**
 * Abstract class containing common methods used by Propel and Doctrine providers.
 */
abstract class AbstractProvider implements Provider
{
    /**
     * @var PropertyAccessor
     */
    protected $accessor;

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    protected $options = [
        'loc' => [],
        'lastmod' => null,
        'priority' => null,
        'changefreq' => null,
    ];

    public function __construct(UrlGenerator $urlGenerator, array $options)
    {
        $this->urlGenerator = $urlGenerator;
        $this->options = array_merge($this->options, $options);

        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    protected function resultToUrl($result)
    {
        $url = new Url();
        $url->setLoc($this->getResultLoc($result));

        if ($this->options['priority'] !== null) {
            $url->setPriority($this->options['priority']);
        }

        if ($this->options['changefreq'] !== null) {
            $url->setChangefreq($this->options['changefreq']);
        }

        if ($this->options['lastmod'] !== null) {
            $url->setLastmod($this->getColumnValue($result, $this->options['lastmod']));
        }

        return $url;
    }

    protected function getResultLoc($result)
    {
        $route = $this->options['loc']['route'];
        $params = [];

        if (!isset($this->options['loc']['params'])) {
            $this->options['loc']['params'] = [];
        }

        foreach ($this->options['loc']['params'] as $key => $column) {
            $params[$key] = $this->getColumnValue($result, $column);
        }

        return $this->urlGenerator->generate($route, $params);
    }

    protected function getColumnValue($result, $column)
    {
        return $this->accessor->getValue($result, $column);
    }
}
