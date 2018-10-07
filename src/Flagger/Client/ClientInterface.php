<?php

namespace Flagger\Client;

interface ClientInterface
{
    const PLATFORM = 'php';
    const VERSION = '1.0.0';

    const DEFAULT_SERVER_URL = 'http://localhost:5000';
    const OBJECT_GATE_VALUES_ENDPOINT = '/v2/object-values/';

    /**
     * @param array $data
     *
     * @return array
     */
    public function sendRequest($data);
}
