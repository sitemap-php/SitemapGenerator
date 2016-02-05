<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\UrlGenerator;

/**
 * Populate a sitemap using a Propel model.
 *
 * The options available are the following:
 *  * model: the model to use (FQCN)
 *  * filters: array of method to apply on the query
 *  * loc: array describing how to generate an URL with the router
 *  * lastmod: name of the lastmod attribute (can be null)
 *
 * Exemple:
 *  [
 *      'model'     => 'Acme/Model/News',
 *      'filters'   => ['filterByIsValid'],
 *      'lastmod'   => 'updatedAt',
 *      'loc'       => ['route' => 'show_news', 'params' => ['id' => 'slug']],
 *  ]
 *
 * NOTE This provider uses an "on demand" hydration.
 */
class Propel extends AbstractProvider implements \IteratorAggregate
{
    protected $options = [
        'model'         => null,
        'loc'           => [],
        'filters'       => [],
        'lastmod'       => null,
    ];

    /**
     * @param array $options The options (see the class comment).
     */
    public function __construct(UrlGenerator $urlGenerator, array $options)
    {
        parent::__construct($urlGenerator, $options);

        if (!class_exists($options['model'])) {
            throw new \LogicException('Can\'t find class ' . $options['model']);
        }
    }

    public function getIterator()
    {
        $query = $this->getQuery($this->options['model']);

        // apply filters on the query
        foreach ($this->options['filters'] as $filter) {
            $query->$filter();
        }

        // and populate the sitemap!
        foreach ($query->find() as $result) {
            yield $this->resultToUrl($result);
        }
    }

    protected function getQuery($model)
    {
        return \PropelQuery::from($model)->setFormatter('PropelOnDemandFormatter');
    }
}
