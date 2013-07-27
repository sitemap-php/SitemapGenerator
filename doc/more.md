## Going further

### Dumpers and formatters

The dumper is the class which takes care of the sitemap's persistance (in
memory, in a file, etc.) and the formatter formats the sitemap.

Currently, the following dumpers are implemented:

  * FileDumper: dumps the sitemap into a file
  * GzFileDumper: dumps the sitemap into a gz compressed file
  * MemoryDumper: dumps the sitemap in memory

And the following formatters are implemented:

  * TextFormatter: formats the sitemap as a simple text file that contains one URL per line
  * XmlFormatter: formats a classic XML sitemap
  * RichXmlFormatter: formats a rich XML sitemap
  * SpacelessFormatter: wraps another formatter and remove the \n and \t characters

The dumpers must implement the DumperInterface and the formatters the
FormatterInterface.

The default sitemap service uses a GzFileDumper and a XmlFormatter. You can
change this by overriding the sitemap service definition:

### Images and videos

Images and videos are two objects that are embeddable in sitemaps, and
fortunately, this bundle supports both of them.

Just look the [Image](https://github.com/K-Phoen/SitemapGenerator/blob/master/Entity/Image.php) and [Video](https://github.com/K-Phoen/SitemapGenerator/blob/master/Entity/Video.php) class to know how to use them.

### Base host issues

To ensure that all the URLs added in the sitemap are absolute, the generator
accepts a `base_host` parameter, which will be prepended to URLs when needed:

```php
$sitemap = new Sitemap($dumper, $formatter, $base_host = 'http://www.website.com');
```

### Sitemap index

In order to be able to generate a sitemap index, the sitemap generator
configuration must follow these rules:

  * use a `DumperFileInterface` instance as dumper (like `FileDumper` or
    `GzFileDumper`)
  * use a `SitemapIndexFormatterInterface` instance as formatter (like
    `XmlFormatter` or `RichXmlFormatter`
  * and have a `limit` parameter strictly greater to `0`.

If all these rules are respected, the generator will build a sitemap index.

Here is how you prepare the generator for a sitemap index:

```php
$sitemap = new Sitemap($dumper, $formatter, $base_host = 'http://www.website.com', $sitemapindex_base_host = 'http://www.website.com/sitemap', $limit = 50000);
```
