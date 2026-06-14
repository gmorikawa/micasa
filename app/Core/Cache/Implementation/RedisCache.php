<?php

namespace App\Core\Cache\Implementation;

use App\Core\Cache;
use Illuminate\Support\Facades\Cache as CacheFacade;

class RedisCache implements Cache
{
    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        CacheFacade::store('redis')->put($key, $value, now()->addSeconds($ttl));
    }

    public function get(string $key): mixed
    {
        return CacheFacade::store('redis')->get($key);
    }

    public function delete(string $key): void
    {
        CacheFacade::store('redis')->forget($key);
    }

    public function clear(): void
    {
        CacheFacade::store('redis')->getStore()->flush();
    }
}