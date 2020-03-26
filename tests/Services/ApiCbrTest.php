<?php

namespace Course\Test\Services;

use Course\GuzzleClient;
use Course\Services\ApiCbr;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ApiCbrTest extends TestCase
{
    public function testQuoteParseCorrectResult()
    {
        $response = '<?xml version="1.0" encoding="windows-1251"?>
<ValCurs Date="25.10.2019" name="Foreign Currency Market">
    <Valute ID="R01235">
        <NumCode>840</NumCode>
        <CharCode>USD</CharCode>
        <Nominal>1</Nominal>
        <Name>Доллар США</Name>
        <Value>63,8600</Value>
    </Valute>
    <Valute ID="R01239">
        <NumCode>978</NumCode>
        <CharCode>EUR</CharCode>
        <Nominal>1</Nominal>
        <Name>Евро</Name>
        <Value>71,1400</Value>
    </Valute>
</ValCurs>';
        $result = [
            'USD' => "638600",
            'EUR' => "711400",
        ];
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $response)
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);
        $api = new ApiCbr($guzzle);
        $response = $api->getQuotes(1420070400);
        $this->assertEquals($response, $result);
    }

}
