<?php

namespace SitemapGenerator\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\RouterInterface;

use SitemapGenerator\Entity\Url;
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
class DoctrineProvider implements ProviderInterface
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
     * @param Entitymanager $em Doctrine entity manager.
     * @param RouterInterface $router The application router.
     * @param array $options The options (see the class comment).
     */
    public function __construct(EntityManager $em, RouterInterface $router, array $options)
    {
        $this->router = $router;
        $this->em = $em;
        $this->options = array_merge($this->options, $options);
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
        $params = array();

        if (!isset($this->options['loc']['params'])) {
            $this->options['loc']['params'] = array();
        }

        foreach ($this->options['loc']['params'] as $key => $column) {
            $params[$key] = $this->getColumnValue($result, $column);
        }

        return $this->router->generate($route, $params);
    }

    protected function getColumnValue($result, $column)
    {
        $method = 'get'.$column;

        if (!method_exists($result, $method)) {
            throw new \RuntimeException(sprintf('"%s" method not found in "%s"', $method, $this->options['entity']));
        }

        return $result->$method();
    }

    protected function getQuery($entity, $method)
    {
        $repo = $this->em->getRepository($entity);
        $query = call_user_func(array($repo, $method));

        if (!$query instanceof Query) {
            throw new \RuntimeException(sprintf('Expected instance of Query, got %s (see method %s:%s)', get_class($query), $entity, $method));
        }

        return $query;
    }
}
