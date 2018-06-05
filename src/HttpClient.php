<?php

namespace Hellovoid\Orangepay;

use Hellovoid\Orangepay\Exception\HttpException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;


class HttpClient
{
    private $apiUrl;
    private $auth;
    private $transport;
    /** @var RequestInterface */
    private $lastRequest;
    /** @var ResponseInterface */
    private $lastResponse;
    public function __construct($apiUrl, Authentication $auth, ClientInterface $transport)
    {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->auth = $auth;
        $this->transport = $transport;
    }
    public function getLastRequest()
    {
        return $this->lastRequest;
    }
    public function getLastResponse()
    {
        return $this->lastResponse;
    }
    public function get($path, array $params = [])
    {
        return $this->request('GET', $path, $params);
    }
    public function put($path, array $params = [])
    {
        return $this->request('PUT', $path, $params);
    }
    public function post($path, array $params = [])
    {
        return $this->request('POST', $path, $params);
    }
    public function delete($path, array $params = [])
    {
        return $this->request('DELETE', $path, $params);
    }
    // private
    private function request($method, $path, array $params = [])
    {
        if ('GET' === $method) {
            $path = $this->prepareQueryString($path, $params);
        }
        $request = new Request($method, $this->prepareUrl($path));
        return $this->send($request, $params);
    }
    private function send(RequestInterface $request, array $params = [])
    {
        $this->lastRequest = $request;
        $options = $this->prepareOptions(
            $request->getMethod(),
            $request->getRequestTarget(),
            $params
        );
        try {
            $this->lastResponse = $this->transport->send($request, $options);
        } catch (RequestException $e) {
            throw HttpException::wrap($e);
        }

        return $this->lastResponse;
    }
    private function prepareQueryString($path, array $params = [])
    {
        if ( ! $params) {
            return $path;
        }

        $path .= false === strpos($path, '?') ? '?' : '&';
        $path .= http_build_query($params, '', '&');
        return $path;
    }
    private function prepareUrl($path)
    {
        return $this->apiUrl.'/'.ltrim($path, '/');
    }
    private function prepareOptions($method, $path, array $params = [])
    {
        $options = [];
        if ($params) {
            $options[RequestOptions::JSON] = $params;
        }

        $defaultHeaders = [
            'User-Agent' => 'orangepay-php/1.0',
        ];

        $options[RequestOptions::HEADERS] = $defaultHeaders + $this->auth->getRequestHeaders(
            $method,
            $path
        );

        return $options;
    }
}