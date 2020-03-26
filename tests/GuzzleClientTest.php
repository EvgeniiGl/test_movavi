<?php

namespace Course\Test;

use Course\ApiException;
use Course\GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class GuzzleClientTest extends TestCase
{

    public function testAsyncRequestGuzzleReturnCorrectResponse()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
            new Response(202, ['Content-Length' => 0])
        ]);

        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);
        $response = $guzzle->asyncRequest(['/', '/']);
        $this->assertEquals($response[0]->getStatusCode(), 200);
        $this->assertEquals($response[1]->getStatusCode(), 202);
        $this->assertEquals($response[0]->getBody()->getContents(), 'Hello, World');
        $this->assertEquals($response[1]->getBody()->getContents(), '');
    }

    public function testAsyncRequestGuzzleShouldThrowException()
    {
        $mock = new MockHandler([
            new RequestException('', new Request('GET', 'test'))
        ]);
        $this->expectException(ApiException::class);
        $this->expectErrorMessage('Invalid api response');
        $handlerStack = HandlerStack::create($mock);
        $guzzle = new GuzzleClient(['handler' => $handlerStack]);
        $guzzle->asyncRequest(['/']);

    }
}
