<?php

namespace SitemapGenerator\Tests\Provider;

use \Mockery;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Tests\Fixtures\News;

abstract class AbstractProviderTest extends \PHPUnit_Framework_TestCase
{
    protected function getRouter(array $results)
    {
        $router = $this->getMockBuilder('\Symfony\Component\Routing\RouterInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $valueMap = array();
        foreach ($results as $news) {
            $valueMap[] = array('show_news', array(
                'id' => $news->slug
            ), false, '/news/'.$news->slug);
        }

        $router
            ->expects($this->any())
            ->method('generate')
            ->will($this->returnValueMap($valueMap));

        return $router;
    }

    protected function getSitemap(array $urls)
    {
        $sitemap = $this->getMockBuilder('\SitemapGenerator\Sitemap\Sitemap')
            ->disableOriginalConstructor()
            ->getMock();

        if (empty($urls)) {
            $sitemap
                ->expects($this->never())
                ->method('add');
        } else {
            foreach ($urls as $at => $url) {
                $sitemap
                    ->expects($this->at($at))
                    ->method('add')
                    ->with($this->equalTo($url));
            }
        }

        return $sitemap;
    }

    public function newsDataProvider()
    {
        $first = new News();
        $first->slug = 'first';

        $second = new News();
        $second->slug = 'second';

        $urlFirst = new Url();
        $urlFirst->setLoc('/news/first');

        $urlSecond = new Url();
        $urlSecond->setLoc('/news/second');

        return array(
            array(array(), array()),
            array(array($first, $second), array($urlFirst, $urlSecond)),
        );
    }
}
