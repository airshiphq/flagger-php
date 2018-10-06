<?php

namespace Airship\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;

class GuzzleClient implements ClientInterface
{

    /**
     * @var string
     */
    private $envKey;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $envKey
     * @param string $serverUrl
     * @param array  $config An array of Guzzle Client options for configuring the client
     */
    public function __construct($envKey, array $config = [], $serverUrl = self::DEFAULT_SERVER_URL)
    {
        $this->envKey = $envKey;

        $this->client = new Client(array_replace_recursive([
            'base_uri' => $serverUrl,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'timeout' => 10,
            'connect_timeout' => 10,
        ], $config));
    }

    public function sendRequest($data)
    {
        $response = null;

        try {
            $options['body'] = json_encode($data);
            $response = $this->client->request(
                'POST',
                self::OBJECT_GATE_VALUES_ENDPOINT . $this->envKey,
                $options
            );
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            if ($statusCode === 403) {
                throw new \Exception('Failed to connect to Airship edge server');
            } else {
                throw $e;
            }
        } catch (BadResponseException $e) {
            throw new \Exception('Failed to connect to Airship edge server');
        }

        return json_decode((string) $response->getBody(), true);
    }
}
