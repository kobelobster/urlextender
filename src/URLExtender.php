<?php
namespace tzfrs\URLExtender;
use Gilbitron\Util\SimpleCache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use tzfrs\URLExtender\Exceptions\URLExtenderException;

/**
 * This class is the class that can extend URLs etc. It has a method to extend URLs and handles caching.
 *
 * Class URLExtender
 * @package tzfrs\URLExtender
 * @version 0.0.1
 * @author Theo Tzaferis <theo.tzaferis@active-value.de>
 * @licence MIT
 *
 */
class URLExtender
{
    /**
     * The object used for caching
     * @var SimpleCache|null
     */
    protected $cache        = null;

    /**
     * If caching should be used. Defaults to true
     * @var bool
     */
    protected $usingCache   = true;

    /**
     * The cache path.
     * @var string
     */
    protected $cachePath    = '/tmp/';

    /**
     * How long the caching should be done
     *
     * @var int
     */
    protected $cacheTime    = 3600;

    /**
     * The constructor of this class simply defines if and how the caching works. It's possible to disable caching,
     * or, if enabled, set the cachePath and the cache duration of the library.
     *
     * The method takes the parameters usingCache, cachePath and cacheTime and sets the protected members of the class
     * to the values passed as a parameter. If the usingCache parameter has been set to true, an instance of SimpleCache
     * is created and initialized, and the cachePath and cacheTime are getting set to the according properties of the
     * class that handles the caching
     *
     * @see SimpleCache
     *
     * @param bool|true $usingCache Whether to use caching
     * @param string $cachePath Where to save cache files
     * @param int $cacheTime How long the caching should be
     */
    public function __construct($usingCache = true, $cachePath = '/tmp/', $cacheTime = 3600)
    {
        $this->usingCache   = $usingCache;
        $this->cachePath    = $cachePath;
        $this->cacheTime    = $cacheTime;

        if ($this->usingCache === true) {
            $this->cache = new SimpleCache();
            $this->cache->cache_path = $this->cachePath;
            $this->cache->cache_time = $this->cacheTime;
        }
    }

    /**
     * This method is used to extend an URL if a location header is set in the HTTP Headers
     *
     * The method takes the URL that should be extended as a paremter and firstly builds a cacheName by the URL, because
     * if caching is activated the library tries to get the result of the method from cache instead of requesting it
     * newly every time. If caching is activated the method get_cache of the caching class is used to try to get cache
     * data. If cached data is available it is returned. Otherwise a new request is made using the request method of Guzzle.
     * At this state it's possible that Exceptions are thrown due to HTTP Stauts codes so a Client and Server Exception
     * is caught and thrown again as URLExtenderException. If no exception is thrown and caching isn't activated
     * the location parameter (if present) is returned, otherwise the original URL. If caching is activated the location
     * is saved to cache and then returned
     *
     * @see SimpleCache::get_cache
     * @see SimpleCache::set_cache
     * @see MessageInterface::getHeaderline
     * @see ClientException
     * @see ServerException
     *
     * @param $url
     * @return bool|string
     * @throws URLExtenderException
     */
    public function extendURL($url)
    {
        $cacheName = $this->getCacheName($url);


        if ($this->usingCache === true) {
            $location = $this->cache->get_cache($cacheName);
            if ($location !== false) {
                return $location;
            }
        }

        try {
            $result     = $this->request($url);
            $location   = $result->getHeaderLine('Location') !== '' ? $result->getHeaderLine('Location') : $url;
            if ($this->usingCache !== true) {
                return $location;
            }

            $this->cache->set_cache($cacheName, $location);
            return $location;
        } catch (ClientException $e) {
            throw new URLExtenderException($e->getMessage(), $e->getCode());
        } catch (ServerException $e) {
            throw new URLExtenderException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * This method make simple Requests using the GuzzleClient and disalbeds redirects to get the Location Header
     *
     * @see Client
     *
     * @param string $url The URL that will get requested
     * @return \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Psr7\Stream
     */
    protected function request($url)
    {
        $client = new Client();
        return $client->head($url, ['allow_redirects' => false]);
    }

    /**
     * This method is used to build a unique cache name, so it doens't interfere with other libraries saving cache
     * to the /tmp/ folder
     * @param $string
     * @return string
     */
    protected function getCacheName($string)
    {
        return md5(__CLASS__ . $string);
    }

    /**
     * @param boolean $useCache
     * @return URLExtender
     */
    public function setUsingCache($useCache)
    {
        $this->usingCache = $useCache;
        return $this;
    }

    /**
     * @param string $cachePath
     * @return URLExtender
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
        return $this;
    }

    /**
     * @param int $cacheTime
     * @return URLExtender
     */
    public function setCacheTime($cacheTime)
    {
        $this->cacheTime = $cacheTime;
        return $this;
    }
}