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
        $routes = array_map(function($result) {
            return [
                'name'   => 'show_news',
                'params' => ['id' => $result->slug]
            ];
        }, $results);

        return new SimpleProvider($this->getRouter($results), [
            'routes' => $routes,
        ]);
    }
}
