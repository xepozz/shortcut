<?php

declare(strict_types=1);

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * @throws InvalidArgumentException
 */
function cache(
    string|int|Stringable|array $key,
    mixed $value = null,
    int|DateInterval|null $ttl = null,
): mixed {
    /**
     * @var CacheInterface $cache
     */
    $cache = container(CacheInterface::class);

    $key = is_array($key) ? strtr(json_encode($key), '{}()/\@:', '________') : (string) $key;

    if ($cache->has($key)) {
        return $cache->get($key);
    }

    $value = is_callable($value) ? $value() : $value;
    $cache->set($key, $value, $ttl);

    return $value;
}
