<?php

namespace SitemapGenerator\Provider;

use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

use SitemapGenerator\UrlGenerator;

/**
 * Populate a sitemap using a Doctrine entity.
 *
 * The options available are the following:
 *  * entity: the entity to use
 *  * query_method: repository method to build the query (must return a doctrine Query instance)
 *  * loc: array describing how to generate an URL with the router
 *  * lastmod: name of the lastmod attribute (can be null)
 *
 * Exemple:
 *  [
 *      'entity'        => 'AcmeDemoBundle:News',
 *      'query_method'  => 'findForSitemapQuery',
 *      'lastmod'       => 'updatedAt',
 *      'loc'           => ['route' => 'show_news', 'params' => ['id' => 'slug']],
 *  ]
 *
 * NOTE This provider uses an "on demand" hydration.
 */
class Doctrine extends AbstractProvider implements \IteratorAggregate
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected $options = [
        'entity' => null,
        'loc' => [],
        'query_method' => null,
        'lastmod' => null,
        'priority' => null,
        'changefreq' => null,
    ];

    public function __construct(EntityManager $em, UrlGenerator $urlGenerator, array $options)
    {
        parent::__construct($urlGenerator, $options);

        $this->em = $em;
    }

    public function getIterator()
    {
        $query = $this->getQuery($this->options['entity'], $this->options['query_method']);
        $results = $query->iterate();

        // and populate the sitemap!
        while (($result = $results->next()) !== false) {
            yield $this->resultToUrl($result[0]);

            $this->em->detach($result[0]);
        }
    }

    protected function getQuery($entity, $method = null)
    {
        $repo = $this->em->getRepository($entity);

        if ($method !== null) {
            $query = $repo->$method();
        } else {
            $query = $repo->createQueryBuilder('o')->getQuery();
        }

        if ($query instanceof QueryBuilder) {
            $query = $query->getQuery();
        }

        if (!$query instanceof Query) {
            throw new \RuntimeException(sprintf('Expected instance of Query, got %s (see method %s:%s)', get_class($query), $entity, $method));
        }

        return $query;
    }
}
