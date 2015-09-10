<?php
namespace tzfrs\URLExtender\Tests;

use tzfrs\URLExtender\URLExtender;
use tzfrs\URLExtender\Exceptions\URLExtenderException;

/**
 * Class URLExtenderTest
 * @package tzfrs\URLExtender\Tests
 * @version 0.0.2
 * @author Theo Tzaferis <theo.tzaferis@active-value.de>
 * @licence MIT
 *
 */
class URLExtenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var URLExtender
     */
    protected static $urlExtender;

    public static function setUpBeforeClass()
    {
        self::$urlExtender = new URLExtender();
    }

    public function testExtendURL()
    {
        $response = self::$urlExtender->extendURL('https://t.co/XdXRudPXH5');
        $this->assertEquals('https://blog.twitter.com/2013/rich-photo-experience-now-in-embedded-tweets-3', $response);

        $response = self::$urlExtender->extendURL('https://blog.twitter.com/2013/rich-photo-experience-now-in-embedded-tweets-3');
        $this->assertEquals('https://blog.twitter.com/2013/rich-photo-experience-now-in-embedded-tweets-3', $response);

    }

    public function testExtendURLException()
    {
        $this->setExpectedException(URLExtenderException::class);
        self::$urlExtender->extendURL('http://httpstat.us/404');
    }

    public function testExtendURLNull()
    {
        $this->setExpectedException(URLExtenderException::class);
        self::$urlExtender->extendURL(null);
    }
}