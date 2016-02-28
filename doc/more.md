## Going further

### Dumpers and formatters

The dumper is the class which takes care of the sitemap's persistance (in
memory, in a file, etc.) and the formatter formats the sitemap.

Currently, the following dumpers are implemented:

  * File: dumps the sitemap into a file
  * GzFile: dumps the sitemap into a gz compressed file
  * Memory: dumps the sitemap in memory

And the following formatters are implemented:

  * Text: formats the sitemap as a simple text file that contains one URL per line
  * Xml: formats a classic XML sitemap
  * RichXml: formats a rich XML sitemap
  * Spaceless: wraps another formatter and remove the \n and \t characters

The dumpers must implement the `Dumper` interface and the formatters the
`Formatter` interface.

The default sitemap service uses a `GzFile` dumper and a `Xml` formatter. You can
change this by overriding the sitemap service definition:

### Images and videos

Images and videos are two objects that are embeddable in sitemaps, and
fortunately, this bundle supports both of them.

Just look the [Image](https://github.com/K-Phoen/SitemapGenerator/blob/master/Entity/Image.php) and [Video](https://github.com/K-Phoen/SitemapGenerator/blob/master/Entity/Video.php) class to know how to use them.

### Sitemap index

In order to be able to generate a sitemap index, the sitemap generator
configuration must follow these rules:

  * use a `\SitemapGenerator\Dumper\FileDumper` instance as dumper (like
    `\SitemapGenerator\Dumper\File` or `\SitemapGenerator\Dumper\GzFile`)
  * use a `\SitemapGenerator\Formatter\SitemapIndex` instance as formatter (like
    `SitemapGenerator\Formatter\Xml` or `SitemapGenerator\Formatter\RichXml`
  * and have a `limit` parameter strictly greater to `0`.

If all these rules are respected, the generator will build a sitemap index.

Here is how you prepare the generator for a sitemap index:

```php
$sitemap = new Sitemap($dumper, $formatter, $base_host = 'http://www.website.com', $sitemapIndexBaseHost = 'http://www.website.com/sitemap', $limit = 50000);
```
