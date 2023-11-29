<?php

namespace App\Services;

use App\Api\MinecraftClient;


class MinecraftService {

    const UUID_TO_NAME = "session/minecraft/profile/<uuid>";

    private MinecraftClient $minecraftClient;

    public function __construct(MinecraftClient $minecraftClient) {
        $this->minecraftClient = $minecraftClient;    
    }

    public function resolveUuid(string $uuid): string|null {
        $result = $this->minecraftClient->retrieve(str_replace("<uuid>", $uuid, self::UUID_TO_NAME));
        
        return $result['name'] ?? null;
    }
}