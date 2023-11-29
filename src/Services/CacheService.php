<?php 

namespace App\Services;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CacheService {

    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function getItem(string $key, int $expiration = 3600, $data = null): mixed {
        $this->cache->get($key, function (ItemInterface $item) use ($expiration, $data) {
            $item->expiresAfter($expiration);
            return $data;
        });
    } 
}