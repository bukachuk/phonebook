<?php

namespace Project\Service;

use GuzzleHttp\Client;
use Nette\Caching\Cache;

/**
 * Class Hostaway
 * Hostaway API Client
 *
 * @package Project\Service
 */
class Hostaway
{
    const BASE_URL = 'https://api.hostaway.com';
    const TIMEOUT = 5;
    const CACHE_TIMEOUT = 3600;
    const CACHE_DIR = 'var/cache';

    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => self::TIMEOUT,
        ]);
    }

    /**
     * Get countries array
     *
     * @return array
     */
    public function getCountries()
    {
        return $this->get('countries');
    }

    /**
     * Get timeZones array
     *
     * @return array
     */
    public function getTimezones()
    {
        return $this->get('timezones');
    }

    /**
     * GET Request Hostaway API
     * Return cached data if exists
     *
     * @param $uri
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    private function get($uri){
        $storage = new \Nette\Caching\Storages\FileStorage(self::CACHE_DIR);
        $cache = new Cache($storage);

        if($value = $cache->load($uri)){
            return $value;
        }

        $response = $this->client->request('GET', $uri);
        $response = $this->parseResponse($response);

        $cache->save($uri, $response, [Cache::EXPIRE => self::CACHE_TIMEOUT]);

        return $response;
    }

    /**
     * Parse JSON Response
     *
     * @param $response
     * @return mixed
     */
    private function parseResponse($response)
    {
        $result = json_decode($response->getBody()->getContents(), true);

        return $result;
    }
}