<?php

namespace Airship;

use Airship\Client\ClientInterface;
use Mockery;

/**
 * @covers \Airship\Airship
 */
class AirshipTest extends TestCase
{

    public function test_determines_if_is_eligible()
    {
        $client = Mockery::mock(ClientInterface::class);

        $client
            ->shouldReceive('sendRequest')
            ->andReturn([
                'isEligible' => false,
            ]);

        $airship = new Airship($client);

        $user = [
            'type' => 'User',
            'id' => '1234',
            'display_name' => 'ironman@stark.com',
        ];

        self::assertFalse($airship->flag('paypal-pay')->isEligible($user));
    }

    public function test_gets_treatment()
    {
        $client = Mockery::mock(ClientInterface::class);

        $client
            ->shouldReceive('sendRequest')
            ->andReturn([
                'treatment' => 'off',
            ]);

        $airship = new Airship($client);

        $user = [
            'type' => 'User',
            'id' => '1234',
            'display_name' => 'ironman@stark.com',
        ];

        self::assertEquals('off', $airship->flag('bitcoin-pay')->getTreatment($user));
    }
}
