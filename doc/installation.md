## Installation

The recommended way to install SitemapGenerator is through composer.

Just create a `composer.json` file for your project:

```json
{
    "require": {
        "kphoen/sitemap-generator": "1.1.*"
    }
}
```

And run these two commands to install it:

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar install
```


Now you can add the autoloader, and you will have access to the library:

```php
require 'vendor/autoload.php';
```

If you don't use neither **Composer** nor a _ClassLoader_ in your application, just require the provided autoloader:

```php
require_once 'src/autoload.php';
```

You're done.

## Next steps

[Return to the index](https://github.com/K-Phoen/SitemapGenerator/blob/master/doc/index.md) or
[start building sitemaps](https://github.com/K-Phoen/SitemapGenerator/blob/master/doc/usage.md)
