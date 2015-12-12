<?php

namespace SitemapGenerator\Tests\Provider;

use SitemapGenerator\Provider\SimpleProvider;

class SimpleProviderTest extends AbstractProviderTest
{
    /**
     * @dataProvider newsDataProvider
     */
    public function testPopulateWithNoResults(array $news, array $newsUrls)
    {
        $sitemap = $this->getSitemap($newsUrls);
        $provider = $this->getNewsProvider($news);

        $provider->populate($sitemap);
    }

    protected function getNewsProvider(array $results)
    {
        $routes = array();
        foreach ($results as $result) {
            $routes[] = array(
                'name' => 'show_news',
                'params' => array('id' => $result->slug)
            );
        }
        return new SimpleProvider($this->getRouter($results), array(
            'routes' => $routes,
        ));
    }
}
