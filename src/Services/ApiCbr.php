<?php


namespace Course\Services;

use Course\Helpers\Count;
use Course\HttpClient;

/**
 * Class ApiCbr
 * @package Course\Services
 */
class ApiCbr implements ApiService
{
    /**
     * @var string url api "Центрального банка Российской Федерации"
     */
    private $url = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req={date}';
    /**
     * @var string format date
     */
    private $dateFormat = 'd/m/Y';
    /**
     * @var array configs
     */
    public $config;
    /**
     * @var HttpClient
     */
    private $client;
    /**
     * @var array prepared urls for the API request
     */
    private $urls;

    /**
     * ApiCbr constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
        $this->config = require __DIR__ . '../../config.php';
    }

    /**
     * @return array prepared urls for the API request
     */
    public function getUrls(): array
    {
        return $this->urls;
    }

    /**
     * @param int $date timestamp date
     * @return array urls for request
     */
    private function setUrls(int $date):void
    {
        $dateString = date($this->dateFormat, $date);
        $this->urls = [str_replace("{date}", $dateString, $this->url)];
    }

    /**
     * @param array $urls
     * @return array responses
     */
    private function request(array $urls): array
    {
        return $this->client->asyncRequest($urls);
    }

    /**
     * @param array $response
     * @return array
     */
    private function parseResponse(array $response): array
    {
        $xml = simplexml_load_string($response[0]->getBody()->getContents());
        $result = [];
        foreach ($xml as $valute) {
            $code = (string)$valute->CharCode;
            if (in_array($code, $this->config['codes'], true)) {
                $result[$code] = Count::conversion($valute->Value);
            }
        }
        return $result;
    }

    /**
     * @param int $date timestamp
     * @return array array the rate EUR/RUB and USD/RUB [code=>value]
     */
    public function getQuotes(int $date): array
    {
        $this->setUrls($date);
        $response = $this->request($this->getUrls());
        return $this->parseResponse($response);
    }
}
