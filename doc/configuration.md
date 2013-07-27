## Sitemap configuration

At this point, you should be able to generate sitemaps : `app/console sitemap:generate`.
But as we did not tell the bundle how to add entries in that sitemap, it's
empty.


### Add entries to the sitemap

#### Providers

In order to support any kind of datasource, the sitemap uses providers to fetch
the data.

Exemple provider:

```php
<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Provider\ProviderInterface;
use SitemapGenerator\Sitemap\Sitemap;


class DummyProvider implements ProviderInterface
{
    public function populate(Sitemap $sitemap)
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setChangefreq(Url::CHANGEFREQ_NEVER);
        $url->setLastmod('2012-12-19 02:28');
        $sitemap->add($url);
    }
}
```

All the providers implement the `ProviderInterface`, which define the
`populate()` method.


#### Propel provider

A propel provider is included in the bundle. It allows to populate a sitemap
with the content of a table.


## Next steps

[Return to the index](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Resources/doc/index.md)
or [go further](https://github.com/K-Phoen/KPhoenSitemapBundle/blob/master/Resources/doc/more.md)
