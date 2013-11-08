<?php

namespace SitemapGenerator\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\RouterInterface;

use SitemapGenerator\Sitemap\Sitemap;

/**
 * Populate a sitemap using a Doctrine entity.
 *
 * The options available are the following:
 *  * entity: the entity to use
 *  * query_method: repository method to build the query (must return a doctrine Query instance)
 *  * loc: array describing how to generate an URL with the router
 *  * lastmod: name of the lastmod attribute (can be null)
 *  * priority: the priority to apply to all the elements (can be null)
 *  * changefreq: the changefreq to apply to all the elements (can be null)
 *
 * Exemple:
 *  array(
 *      'entity'        => 'AcmeDemoBundle:News',
 *      'query_method'  => 'findForSitemapQuery',
 *      'lastmod'       => 'updatedAt',
 *      'priority'      => 0.6,
 *      'loc'           => array('route' => 'show_news', 'params' => array('id' => 'slug')),
 *  )
 *
 * @note This provider uses an "on demand" hydration.
 */
class DoctrineProvider extends AbstractProvider
{
    protected $router;
    protected $em;

    protected $options = array(
        'entity'        => null,
        'loc'           => array(),
        'query_method'  => null,
        'lastmod'       => null,
        'priority'      => null,
        'changefreq'    => null,
    );

    /**
     * Constructor
     *
     * @param Entitymanager   $em      Doctrine entity manager.
     * @param RouterInterface $router  The application router.
     * @param array           $options The options (see the class comment).
     */
    public function __construct(EntityManager $em, RouterInterface $router, array $options)
    {
        parent::__construct($router, $options);

        $this->em = $em;
    }

    /**
     * Populate a sitemap using a Doctrine entity.
     *
     * @param Sitemap $sitemap The current sitemap.
     */
    public function populate(Sitemap $sitemap)
    {
        $query = $this->getQuery($this->options['entity'], $this->options['query_method']);
        $results = $query->iterate();

        // and populate the sitemap!
        while (($result = $results->next()) !== false) {
            $sitemap->add($this->resultToUrl($result[0]));

            $this->em->detach($result[0]);
        }
    }

    protected function getQuery($entity, $method)
    {
        $repo = $this->em->getRepository($entity);
        $query = $repo->$method();

        if (!$query instanceof Query) {
            throw new \RuntimeException(sprintf('Expected instance of Query, got %s (see method %s:%s)', get_class($query), $entity, $method));
        }

        return $query;
    }
}
