<?php

namespace Hellovoid\Orangepay;


class Authentication
{
    private $apiKey;

    /**
     * Authentication constructor.
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $method
     * @param $path
     * @return array
     */
    public function getRequestHeaders($method = null, $path = null)
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
        ];
    }
}