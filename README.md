# Silex Version ServiceProvider

[![Code Climate](https://codeclimate.com/github/frostieDE/silex-assetic-serviceprovider/badges/gpa.svg)](https://codeclimate.com/github/frostieDE/silex-validator-serviceprovider)

ServiceProvider for Silex which enables Assetic. If Twig is enabled, it enables Twig syntax to
include assets. If in debug mode, assets are dumped automatically on each request.

When using production mode, you can dump all assets by using the `assetic:dump` command.

## Installation

```
$ composer require frostiede/silex-assetic-serviceprovider
```

Afterwards, register the ServiceProvider:

```php
$app->register(new AsseticServiceProvider(), [
    'assetic.options' => [
        'assets_path' => '', // Path to your assets directory (e.g. /app/assets/)
        'web_path' => '' // Path to your web-directory (e.g. /web/)
    ]
]);
```

# Contribution

Any help is welcomed. Feel free to create issues and merge requests :-)

# License

MIT License

# Related

This project was inspired by [mheap/Silex-Assetic](https://github.com/mheap/Silex-Assetic).