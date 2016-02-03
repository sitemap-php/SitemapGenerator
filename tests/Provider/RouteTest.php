<?php

namespace SitemapGenerator\Tests\Provider;

use SitemapGenerator\Provider\Route as RouteProvider;

class RouteProviderTest extends AbstractProviderTest
{
    /**
     * @dataProvider newsDataProvider
     */
    public function testPopulateWithNoResults(array $news, array $newsUrls)
    {
        $provider = $this->getNewsProvider($news);

        $generatedEntries = iterator_to_array($provider->getEntries());
        $this->assertEquals($newsUrls, $generatedEntries);
    }

    protected function getNewsProvider(array $results)
    {
        $routes = array_map(function($result) {
            return [
                'name'   => 'show_news',
                'params' => ['id' => $result->slug]
            ];
        }, $results);

        return new RouteProvider($this->getRouter($results), [
            'routes' => $routes,
        ]);
    }
}
