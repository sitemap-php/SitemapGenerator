<?php

namespace SitemapGenerator\Tests\Provider;

use Mockery;
use SitemapGenerator\Provider\PropelProvider;

class PropelProviderTest extends AbstractProviderTest
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

    protected function getPropelQuery($model, $results)
    {
        $mock = Mockery::mock('overload:PropelQuery');

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

        return $mock;
    }

    protected function getNewsProvider(array $results)
    {
        return new PropelProvider($this->getRouter($results), array(
            'model' => 'SitemapGenerator\Tests\Fixtures\News',
            'loc'   => array('route' => 'show_news', 'params' => array('id' => 'slug')),
        ));
    }
}
