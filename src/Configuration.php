<?php

namespace Hellovoid\Orangepay;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;


class Configuration
{
    private $authentication;
    private $apiUrl;

    /**
     * Creates a new configuration with API key authentication.
     *
     * @param string $apiKey An API key
     * @param $apiUrl
     * @return Configuration A new configuration instance
     */
    public static function apiKey($apiKey, $apiUrl)
    {
        return new static(
            new Authentication($apiKey),
            $apiUrl
        );
    }
    public function __construct(Authentication $authentication, $apiUrl)
    {
        $this->authentication = $authentication;
        $this->apiUrl = $apiUrl;
    }
    /**
     * @param ClientInterface|null $transport
     * @return HttpClient
     */
    public function createHttpClient(ClientInterface $transport = null)
    {
        $httpClient = new HttpClient(
            $this->apiUrl,
            $this->authentication,
            $transport ?: new GuzzleClient()
        );
        return $httpClient;
    }
    public function getAuthentication()
    {
        return $this->authentication;
    }
    public function setAuthentication(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }
    public function getApiUrl()
    {
        return $this->apiUrl;
    }
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }
}