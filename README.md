# URLExtender

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/61a0a09a-dcea-4169-af5c-c4c1d26fc6be/small.png)](https://insight.sensiolabs.com/projects/61a0a09a-dcea-4169-af5c-c4c1d26fc6be)

This library can be used to expand short URLs such as https://t.co/XdXRudPXH5 and get the URL that is behind the short
URL using Guzzles head method and reading the Location header

## Install

Install via [composer](https://getcomposer.org):

```javascript
{
    "require": {
        "tzfrs/urlextender": "0.0.2.1"
    }
}
```

Run `composer install` or `composer update`.

## Getting Started

Note: You can also see the examples.php for more examples.

### Basic parsing

```php
<?php
require __DIR__ . '/vendor/autoload.php';

$urlExtender = new \tzfrs\URLExtender\URLExtender();

try {
    print $urlExtender->extendURL('https://t.co/XdXRudPXH5'); // https://blog.twitter.com/2013/rich-photo-experience-now-in-embedded-tweets-3
    print $urlExtender->extendURL('https://blog.twitter.com/2013/rich-photo-experience-now-in-embedded-tweets-3'); //https://blog.twitter.com/2013/rich-photo-experience-now-in-embedded-tweets-3
    print $urlExtender->extendURL('http://httpstat.us/404'); // URLExtenderException (404)
    print $urlExtender->extendURL('http://httpstat.us/500'); // URLExtenderException (500)
} catch (\tzfrs\URLExtender\Exceptions\URLExtenderException $e) {
    print $e->getCode() . ': ' . $e->getMessage();
}
```
