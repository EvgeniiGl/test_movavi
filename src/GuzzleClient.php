<?php

namespace Course;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Promise;

/**
 * Class GuzzleClient
 * @package Course
 */
class GuzzleClient implements HttpClient
{
    private $client;

    /**
     * GuzzleClient constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->client = new Client($params);
    }

    /**
     * @param array $urls
     * @return array Psr\Http\Message\ResponseInterface[]
     * @throws \Throwable
     */
    public function asyncRequest(array $urls): array
    {
        $promises = [];
        foreach ($urls as $key => $url) {
            $promises[$key] = $this->client->getAsync($url);
        }

        try {
            return Promise\unwrap($promises);
        } catch (TransferException $t) {
            throw new ApiException('Invalid api response', $t->getCode(), $t);
        }
    }

}
