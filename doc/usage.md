## Sitemap configuration

### Setting up the generator

Here is the minimal code required to get a working sitemap generator:

```php
<?php

use SitemapGenerator\Dumper;
use SitemapGenerator\Formatter;
use SitemapGenerator\Sitemap;

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

use SitemapGenerator\Entity;

class DummyProvider implements \IteratorAggregate
{
    public function getIterator()
    {
        $url = new Entity\Url('http://www.google.fr');
        $url->setChangefreq(Entity\ChangeFrequency::NEVER);
        $url->setLastmod(new \DateTimeImmutable('2012-12-19 02:28'));

        yield $url;
    }
}
```

All the providers must be instance of \Traversable`.

**Note:** If your providers returns a lot of entries, the best-practice is to
use a generator to efficiently yield the entries.


#### Built-in providers

A Propel and a Doctrine provider are included in the library. They allow to
populate a sitemap with the content of a table.


### Registering providers

In order to make the providers usable by the generator, you have to register
them:

```php
#no_run

// …

$sitemap->addProvider(new DummyProvider());
```


### Build the sitemap

Once the sitemap is properly configured and all the providers are registered,
you can build the sitemap:

```php
#no_run

// ...

echo $sitemap->build();
```

As we used a `MemoryDumper`, the sitemap will be built in memory and we will be
able to print it directly. Other dumpers will be able to dump the sitemap on the
filesystem.


### Full example

```php
<?php

use SitemapGenerator\Dumper;
use SitemapGenerator\Entity;
use SitemapGenerator\Formatter;
use SitemapGenerator\Sitemap;

class DummyProvider implements \IteratorAggregate
{
    public function getIterator()
    {
        $url = new Entity\Url('http://www.google.fr');
        $url->setChangefreq(Entity\ChangeFrequency::NEVER);
        $url->setLastmod(new \DateTime('2012-12-19 02:28'));

        yield $url;
    }
}

$sitemap = new Sitemap(new Dumper\Memory(), new Formatter\Xml());
$sitemap->addProvider(new DummyProvider());

echo $sitemap->build();
```


## Next steps

[Return to the index](https://github.com/K-Phoen/SitemapGenerator/blob/master/doc/index.md)
or [go further](https://github.com/K-Phoen/SitemapGenerator/blob/master/doc/more.md)
