<?php

namespace SitemapGenerator\Tests\Entity;

use \Mockery;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Provider\PropelProvider;
use SitemapGenerator\Tests\Fixtures\News;

/**
 * @runTestsInSeparateProcesses
 */
class PropelProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider newsDataProvider
     */
    public function testPopulateWithNoResults(array $news, array $newsUrls)
    {
        $this->getPropelQuery('SitemapGenerator\Tests\Fixtures\News', $news);

        $sitemap = $this->getSitemap($newsUrls);
        $provider = $this->getNewsProvider($news);

        $provider->populate($sitemap);
    }

    /**
     * @expectedException           LogicException
     * @expectedExceptionMessage    Can't find class \Lala
     */
    public function testPopulateWithUnknownModel()
    {
        new PropelProvider($this->getRouter(array()), array(
            'model' => '\Lala',
        ));
    }

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

    protected function getPropelQuery($model, $results)
    {
        $mock = Mockery::mock('overload:\PropelQuery');

        $mock
            ->shouldReceive('from')
            ->once()
            ->with('SitemapGenerator\Tests\Fixtures\News')
            ->andReturn(\Mockery::self());

        $mock
            ->shouldReceive('setFormatter')
            ->once()
            ->with('PropelOnDemandFormatter')
            ->andReturn(\Mockery::self());

        $mock
            ->shouldReceive('find')
            ->once()
            ->andReturn($results);

        return $this->propelMock = $mock;
    }

    protected function getNewsProvider(array $results)
    {
        return new PropelProvider($this->getRouter($results), array(
            'model' => 'SitemapGenerator\Tests\Fixtures\News',
            'loc'   => array('route' => 'show_news', 'params' => array('id' => 'slug')),
        ));
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
