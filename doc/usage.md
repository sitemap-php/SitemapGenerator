## Sitemap configuration

### Setting up the generator

Here is the minimal code required to get a working sitemap generator:

```php
<?php

use SitemapGenerator\Dumper;
use SitemapGenerator\Formatter;
use SitemapGenerator\Sitemap\Sitemap;

$dumper = new Dumper\Memory();
$formatter = new Formatter\Xml();
$sitemap = new Sitemap($dumper, $formatter);
```

At this point, you have a generator ready to build xml-formatted sitemaps and
dump them in memory.

The next step is to configure the generator provisionning.


### Add entries to the sitemap

#### Providers

In order to support any kind of datasource, the sitemap uses providers to fetch
the data.

Exemple provider:

```php
<?php

namespace SitemapGenerator\Provider;

use SitemapGenerator\Entity\Url;
use SitemapGenerator\Provider\Provider;
use SitemapGenerator\Sitemap\Sitemap;

class DummyProvider implements Provider
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

All the providers implement the `Provider` interface, which defines the `populate()` method.


#### Built-in providers

A Propel and a Doctrine provider are included in the library. They allow to
populate a sitemap with the content of a table.


### Registering providers

In order to make the providers usable by the generator, you have to register
them:

```php
<?php

$sitemap->addProvider(new DummyProvider());
```


### Build the sitemap

Once the sitemap is properly configured and all the providers are registered,
you can build the sitemap:

```php
<?php

echo $sitemap->build();
```

As we used a `MemoryDumper`, the sitemap will be built in memory and we will be
able to print it directly. Other dumpers will be able to dump the sitemap on the
filesystem.


### Full example

```php
<?php

use SitemapGenerator\Dumper;
use SitemapGenerator\Entity\ChangeFrequency;
use SitemapGenerator\Entity\Url;
use SitemapGenerator\Formatter;
use SitemapGenerator\Provider\Provider;
use SitemapGenerator\Sitemap\Sitemap;

class DummyProvider implements Provider
{
    public function populate(Sitemap $sitemap)
    {
        $url = new Url();
        $url->setLoc('http://www.google.fr');
        $url->setChangefreq(ChangeFrequency::NEVER);
        $url->setLastmod('2012-12-19 02:28');

        $sitemap->add($url);
    }
}

$dumper = new Dumper\Memory();
$formatter = new Formatter\Xml();
$provider = new DummyProvider();

$sitemap = new Sitemap($dumper, $formatter);
$sitemap->addProvider($provider);

echo $sitemap->build();
```


## Next steps

[Return to the index](https://github.com/K-Phoen/SitemapGenerator/blob/master/doc/index.md)
or [go further](https://github.com/K-Phoen/SitemapGenerator/blob/master/doc/more.md)
