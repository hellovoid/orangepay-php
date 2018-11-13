<?php

namespace Hellovoid\Orangepay;

class Client
{
    private $http;

    public function __construct(HttpClient $http)
    {
        $this->http = $http;
    }

    public static function create(Configuration $configuration)
    {
        return new static(
            $configuration->createHttpClient()
        );
    }

    public function getHttpClient()
    {
        return $this->http;
    }

    public function decodeLastResponse()
    {
        if ($response = $this->http->getLastResponse()) {
            return $this->decodeResponse($response->getBody()->getContents());
        }

        return null;
    }

    public function decodeResponse($response)
    {
        return json_decode($response, true);
    }

    public function getBalance()
    {
        return $this->getAndDecodeData('balance');
    }

    private function getAndDecodeData($path, array $params = [])
    {
        if ($response = $this->http->get($path, $params)) {
            return $this->decodeResponse($response->getBody()->getContents());
        }
    }

    public function initializeCharge(array $attributes = [])
    {
        return $this->postAndDecodeData('charges', $attributes);
    }

    private function postAndDecodeData($path, array $params = [])
    {
        if ($response = $this->http->post($path, $params)) {
            return $this->decodeResponse($response->getBody()->getContents());
        }
    }

    public function getCharge($id)
    {
        return $this->getAndDecodeData('charges/' . $id);
    }

    public function getCharges(array $attributes = [])
    {
        return $this->getAndDecodeData('charges', $attributes);
    }

    public function rebill(array $attributes = [])
    {
        return $this->getAndDecodeData('recurring/charges', $attributes);
    }

    public function refund(array $attributes = [])
    {
        return $this->postAndDecodeData('refunds', $attributes);
    }

    public function transferToCard(array $attributes = [])
    {
        return $this->postAndDecodeData('transfers/card', $attributes);
    }

    public function transferToBitcoinAddress(array $attributes = [])
    {
        return $this->postAndDecodeData('transfers/bitcoin', $attributes);
    }

    public function getTransfer($id)
    {
        return $this->getAndDecodeData('transfers/' . $id);
    }

    public function getTransfers(array $attributes = [])
    {
        return $this->getAndDecodeData('transfers', $attributes);
    }

    public function getRates($direction)
    {
        return $this->getAndDecodeData('rates/' . $direction);
    }
}
