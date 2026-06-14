<?php

namespace App\Core;

interface Cache
{
    public function set(string $key, mixed $value, int $ttl = 3600): void;

    public function get(string $key): mixed;

    public function delete(string $key): void;

    public function clear(): void;
}