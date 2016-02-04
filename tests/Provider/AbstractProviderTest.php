<?php

namespace SitemapGenerator\Tests\Provider;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Tests\Fixtures\News;

abstract class AbstractProviderTest extends \PHPUnit_Framework_TestCase
{
    protected function getRouter(array $results)
    {
        $router = $this->getMock('\SitemapGenerator\UrlGenerator');

        $valueMap = array_map(function(News $news) {
            return [
                'show_news', ['id' => $news->slug], '/news/' . $news->slug
            ];
        }, $results);

        $router
            ->expects($this->any())
            ->method('generate')
            ->will($this->returnValueMap($valueMap));

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
