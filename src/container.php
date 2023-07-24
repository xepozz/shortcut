<?php

declare(strict_types=1);

use Xepozz\Shortcut\State;


/**
 * @psalm-template T
 * @psalm-param string|class-string<T> $id
 * @psalm-return ($id is class-string ? T : mixed)
 */
function container(string $id, bool $optional = false)
{
    $container = State::$container;
    if ($container === null) {
        throw new \RuntimeException('Container is not initialized. Make sure you added bootstrap class.');
    }
    if ($optional && !$container->has($id)) {
        return null;
    }
    return $container->get($id);
}