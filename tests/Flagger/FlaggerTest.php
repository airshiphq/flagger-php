<?php

namespace Flagger;

use Flagger\Client\ClientInterface;
use Mockery;

/**
 * @covers \Flagger\Flagger
 */
class FlaggerTest extends TestCase
{

    public function test_determines_if_is_eligible()
    {
        $client = Mockery::mock(ClientInterface::class);

        $client
            ->shouldReceive('sendRequest')
            ->andReturn([
                'isEligible' => false,
            ]);

        $flagger = new Flagger($client);

        $user = [
            'type' => 'User',
            'id' => '1234',
            'display_name' => 'ironman@stark.com',
        ];

        self::assertFalse($flagger->flag('paypal-pay')->isEligible($user));
    }

    public function test_gets_treatment()
    {
        $client = Mockery::mock(ClientInterface::class);

        $client
            ->shouldReceive('sendRequest')
            ->andReturn([
                'treatment' => 'off',
            ]);

        $flagger = new Flagger($client);

        $user = [
            'type' => 'User',
            'id' => '1234',
            'display_name' => 'ironman@stark.com',
        ];

        self::assertEquals('off', $flagger->flag('bitcoin-pay')->getTreatment($user));
    }
}
