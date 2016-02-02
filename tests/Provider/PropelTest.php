<?php

namespace SitemapGenerator\Tests\Provider;

use Mockery;
use SitemapGenerator\Provider\Propel as PropelProvider;

class PropelTest extends AbstractProviderTest
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
     * @expectedException           \LogicException
     * @expectedExceptionMessage    Can't find class \Lala
     */
    public function testPopulateWithUnknownModel()
    {
        new PropelProvider($this->getRouter([]), [
            'model' => '\Lala',
        ]);
    }

    protected function getPropelQuery($model, $results)
    {
        $mock = Mockery::mock('overload:PropelQuery');

        $mock
            ->shouldReceive('from')
            ->once()
            ->with($model)
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
        return new PropelProvider($this->getRouter($results), [
            'model' => 'SitemapGenerator\Tests\Fixtures\News',
            'loc'   => ['route' => 'show_news', 'params' => ['id' => 'slug']],
        ]);
    }
}
