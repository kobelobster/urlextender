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
