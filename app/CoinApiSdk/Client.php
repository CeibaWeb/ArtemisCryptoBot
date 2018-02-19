<?php

namespace App\CoinApiSdk;

use GuzzleHttp\Client as Http;

class Client
{
    protected $http;

    public function __construct()
    {
        $args = [
            //'headers' => ['X-CoinAPI-Key' => config('services.coinapi.key')],
            'base_uri' => 'https://min-api.cryptocompare.com/',
        ];

        $this->http = new Http($args);
    }

    protected function request($type, $uri, $args = null)
    {
        $response = $args ? $this->http->request($type, $uri, ['body' => $args]) : $this->http->request($type, $uri);

        return json_decode((string) $response->getBody(), true);
    }

    public function get($uri)
    {
        return $this->request('get', $uri);
    }

    public function post($uri, $args)
    {
        return $this->request('post', $uri, $args);
    }

    public function getPrices($coins)
    {
        $coin_query = collect($coins)->implode(',');

        $query = http_build_query(
            [
                'fsyms' => $coin_query,
                'tsyms' => 'USD,BTC'
            ]
        );

        return collect($this->get("data/pricemultifull?$query"))['RAW'];
    }
}