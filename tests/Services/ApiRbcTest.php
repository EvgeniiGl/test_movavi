<?php

namespace Course\Test\Services;

use Course\GuzzleClient;
use Course\Services\ApiRbc;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ApiRbcTest extends TestCase
{
    protected $mock;
    protected $guzzle;
    protected $api;

    public function setUp(): void
    {
        $response1 = '{
    "status": 200,
    "meta": {
        "sum_deal": 1.0,
        "source": "cbrf",
        "currency_from": "USD",
        "date": "2020-01-01",
        "currency_to": "RUR"
    },
    "data": {
        "date": "2020-01-01",
        "sum_result": 61.9057,
        "rate1": 61.9057,
        "rate2": 0.0162
    }
}';
        $response2 = '{
    "status": 200,
    "meta": {
        "sum_deal": 1.0,
        "source": "cbrf",
        "currency_from": "EUR",
        "date": "2020-01-01",
        "currency_to": "RUR"
    },
    "data": {
        "date": "2020-01-01",
        "sum_result": 69.3406,
        "rate1": 69.3406,
        "rate2": 0.0144
    }
}';
        $this->mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $response1),
            new Response(200, ['X-Foo' => 'Bar'], $response2)
        ]);

        $handlerStack = HandlerStack::create($this->mock);
        $this->guzzle = new GuzzleClient(['handler' => $handlerStack]);
        $this->api = new ApiRbc($this->guzzle);
    }

    public function testQuoteParseCorrectResult()
    {
        $result = [
            'USD' => "619057",
            'EUR' => "693406",
        ];

        $response = $this->api->getQuotes(1420070400);
        $this->assertEquals($response, $result);
    }

    public function testSetUrlsCreateCorrectUrls()
    {
        $result = [
            "https://cash.rbc.ru/cash/json/converter_currency_rate/?currency_from=USD&currency_to=RUR&source=cbrf&sum=1&date=2015-01-01",
            "https://cash.rbc.ru/cash/json/converter_currency_rate/?currency_from=EUR&currency_to=RUR&source=cbrf&sum=1&date=2015-01-01"
        ];
        $this->api->getQuotes(1420070400);
        $this->assertEquals($this->api->getUrls(), $result);
    }

}
