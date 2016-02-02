<?php

namespace SitemapGenerator\Provider;

use Symfony\Component\Routing\RouterInterface;

use SitemapGenerator\Sitemap\Sitemap;

/**
 * Populate a sitemap using a Propel model.
 *
 * The options available are the following:
 *  * model: the model to use (FQCN)
 *  * filters: array of method to apply on the query
 *  * loc: array describing how to generate an URL with the router
 *  * lastmod: name of the lastmod attribute (can be null)
 *  * priority: the priority to apply to all the elements (can be null)
 *  * changefreq: the changefreq to apply to all the elements (can be null)
 *
 * Exemple:
 *  [
 *      'model'     => 'Acme/Model/News',
 *      'filters'   => ['filterByIsValid'],
 *      'lastmod'   => 'updatedAt',
 *      'priority'  => 0.6,
 *      'loc'       => ['route' => 'show_news', 'params' => ['id' => 'slug']],
 *  ]
 *
 * NOTE This provider uses an "on demand" hydration.
 */
class Propel extends AbstractProvider
{
    protected $router = null;

    protected $options = [
        'model'         => null,
        'loc'           => [],
        'filters'       => [],
        'lastmod'       => null,
        'priority'      => null,
        'changefreq'    => null,
    ];

    /**
     * Constructor
     *
     * @param RouterInterface $router  The application router.
     * @param array           $options The options (see the class comment).
     */
    public function __construct(RouterInterface $router, array $options)
    {
        parent::__construct($router, $options);

        if (!class_exists($options['model'])) {
            throw new \LogicException('Can\'t find class ' . $options['model']);
        }
    }

    /**
     * Populate a sitemap using a Propel model.
     *
     * @param Sitemap $sitemap The current sitemap.
     */
    public function populate(Sitemap $sitemap)
    {
        $query = $this->getQuery($this->options['model']);

        // apply filters on the query
        foreach ($this->options['filters'] as $filter) {
            $query->$filter();
        }

        // and populate the sitemap!
        foreach ($query->find() as $result) {
            $sitemap->add($this->resultToUrl($result));
        }
    }

    protected function getQuery($model)
    {
        return \PropelQuery::from($model)->setFormatter('PropelOnDemandFormatter');
    }
}
