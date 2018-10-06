<?php

namespace Airship\Client;

use Airship\TestCase;
use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * @covers \Airship\Client\GuzzleClient
 */
class GuzzleClientTest extends TestCase
{
    public function test_handles_authentication_errors()
    {
        self::expectException(\Exception::class);

        $client = new GuzzleClient('envKey');

        $this->mockResponses($client, [
            new ClientException('Error', new Request('POST', 'test'), new Response(403)),
        ]);

        $client->sendRequest(['flag' => 'bitcoin-pay', 'entity' => ['type' => 'User', 'id' => '1234']]);
    }

    public function test_handles_400_errors()
    {
        self::expectException(ClientException::class);

        $client = new GuzzleClient('envKey');

        $this->mockResponses($client, [
            new ClientException('Error', new Request('POST', 'test'), new Response(404)),
        ]);

        $client->sendRequest(['flag' => 'bitcoin-pay', 'entity' => ['type' => 'User', 'id' => '1234']]);
    }

    public function test_handles_500_errors()
    {
        self::expectException(\Exception::class);

        $client = new GuzzleClient('envKey');

        $this->mockResponses($client, [
            new BadResponseException('Error', new Request('POST', 'test'), new Response(500)),
        ]);

        $client->sendRequest(['flag' => 'bitcoin-pay', 'entity' => ['type' => 'User', 'id' => '1234']]);
    }

    public function test_returns_parsed_body()
    {
        $client = new GuzzleClient('envKey');

        $response = <<<JSON
{
    "isEnabled": true
}
JSON;

        $this->mockResponses($client, [
            new Response(200, ['Content-Type' => 'application/json'], $response)
        ]);

        $result = $client->sendRequest(['flag' => 'bitcoin-pay', 'entity' => ['type' => 'User', 'id' => '1234']]);

        $expected = [
            'isEnabled' => true,
        ];

        self::assertEquals($expected, $result);
    }

    private function mockResponses(GuzzleClient $guzzleClient, array $responses)
    {
        $replaceWithMock = function (GuzzleClient $guzzleClient) use ($responses) {
            $mock = new MockHandler($responses);

            $handler = HandlerStack::create($mock);

            $guzzleClient->client = new Client(array_merge($guzzleClient->client->getConfig(), ['handler' => $handler]));
        };

        Closure::bind($replaceWithMock, null, GuzzleClient::class)($guzzleClient);
    }
}
