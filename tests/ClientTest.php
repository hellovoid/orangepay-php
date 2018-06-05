<?php

use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = \Hellovoid\Orangepay\Client::create(
            \Hellovoid\Orangepay\Configuration::apiKey(
                'api_token',
                'https://endpoint.ltd/api'
            )
        );
    }

    public function testInitializeCharge()
    {
        try {
            $charge = $this->client->initializeCharge([
                'reference_id' => 'my_unique_reference_id',
                'amount'       => 10.00,
                'currency'     => 'USD',
                'pay_method'   => 'card',
                'description'  => 'Test description #1',
                'email'        => 'payer@domain.ltd',
            ]);
        } catch (\Hellovoid\Orangepay\Exception\UnauthorizedException $exception) {

        } catch (\Hellovoid\Orangepay\Exception\ValidationException $exception) {

        } catch (\Hellovoid\Orangepay\Exception\NotFoundException $exception) {

        } catch (\Hellovoid\Orangepay\Exception\HttpException $exception) {

        }
    }
}