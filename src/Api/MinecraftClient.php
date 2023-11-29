<?php

namespace App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MinecraftClient {

    private Client $client;
    private string $baseUri;
    private int $timeout = 30;

    public function __construct() {
        $this->baseUri = 'https://sessionserver.mojang.com/';
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'timeout' => $this->timeout
        ]);
    }
    
    public function retrieve(string $endpoint, array $query = []): array {
        try {
            $response = $this->client->get($endpoint);

            if($response->getStatusCode() == 404) {
                return [];
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $exception) {
            return ['message' => $exception->getMessage()];   
        }
    }
}