<?php

namespace App\Core\Auth;

use App\Core\Cache;

class Logout
{
    private readonly Cache $cache;

    public function __construct(
        Cache $cache
    )
    {
        $this->cache = $cache;
    }

    public function execute(Token $token): void
    {
        $this->cache->delete((string)$token);
    }
}