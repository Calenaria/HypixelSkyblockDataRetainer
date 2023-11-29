<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class HypixelSkyblockClient {

    private Client $client;
    private float $timeout = 60.0;
    private string $apiKey = '';

    public function __construct(string $baseUri = 'https://api.hypixel.net/', string $apiKey = '5349dd87-7bf5-4538-8517-2c90cabc6195')
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $this->timeout
        ]);

        $this->apiKey = $apiKey;
    }

    public function retrieve(string $endpoint, array $query = []): array {
        try {
            $response = $this->client->get($endpoint, [
                'headers' => [
                    'API-Key' => $this->apiKey
                ],
                'query' => $query
            ]);
        } catch (RequestException) {
            return [];
        }
        return json_decode($response->getBody()->getContents(), true);
    }
}