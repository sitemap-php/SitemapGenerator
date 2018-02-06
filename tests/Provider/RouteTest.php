<?php

namespace SitemapGenerator\Tests\Provider;

use PHPUnit\Framework\TestCase;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Provider\Route as RouteProvider;
use SitemapGenerator\Tests\Fixtures\News;
use SitemapGenerator\UrlGenerator;

class RouteTest extends TestCase
{
    /**
     * @dataProvider newsDataProvider
     */
    public function testPopulateWithNoResults(array $news, array $newsUrls)
    {
        $provider = $this->getNewsProvider($news);

        $generatedEntries = iterator_to_array($provider);
        $this->assertEquals($newsUrls, $generatedEntries);
    }

    private function getNewsProvider(array $results)
    {
        $routes = array_map(function($result) {
            return [
                'name' => 'show_news',
                'params' => ['id' => $result->slug]
            ];
        }, $results);

        return new RouteProvider($this->getRouter($results), $routes);
    }

    private function getRouter(array $results)
    {
        $router = $this->createMock(UrlGenerator::class);

        $valueMap = array_map(function(News $news) {
            return [
                'show_news', ['id' => $news->slug], '/news/' . $news->slug,
            ];
        }, $results);

        $router->method('generate')->willReturnMap($valueMap);

        return $router;
    }

    public function newsDataProvider()
    {
        $first = new News();
        $first->slug = 'first';

        $second = new News();
        $second->slug = 'second';

        $urlFirst = new Url('/news/first');
        $urlSecond = new Url('/news/second');

        return [
            [[], []],
            [[$first, $second], [$urlFirst, $urlSecond]],
        ];
    }
}
