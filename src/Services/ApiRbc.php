<?php


namespace Course\Services;

use Course\Helpers\Count;
use Course\HttpClient;

/**
 * Class ApiRbc
 * @package Course\Services
 */
class ApiRbc implements ApiService
{
    /**
     * @var string url api "РБК"
     */
    private $url = "https://cash.rbc.ru/cash/json/converter_currency_rate/?currency_from=code&currency_to=RUR&source=cbrf&sum=1&date=date_str";
    /**
     * @var string format date
     */
    private $dateFormat = "Y-m-d";
    /**
     * @var array configs
     */
    private $config;

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var array prepared urls for the API request
     */
    private $urls;


    /**
     * ApiRbc constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
        $this->config = require __DIR__ . "../../config.php";
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
    private function setUrls($date): void
    {
        $urls = [];
        $dateString = date($this->dateFormat, $date);
        foreach ($this->config["codes"] as $code) {
            $arrFrom = ["~code~", "~date_str~"];
            $arrTo = [$code, $dateString];
            $urls[] = preg_replace($arrFrom, $arrTo, $this->url);
        }
        $this->urls = $urls;
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
     * @param array $responses
     * @return array
     */
    private function parseResponse(array $responses)
    {
        $result = [];
        foreach ($responses as $response) {
            $val = json_decode($response->getBody()->getContents());
            $code = $val->meta->currency_from;
            if (in_array($code, $this->config['codes'], true)) {
                $result[$code] = Count::conversion($val->data->sum_result);
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
