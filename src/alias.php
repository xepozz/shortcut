<?php

declare(strict_types=1);

use Yiisoft\Aliases\Aliases;


function alias(string $path): string
{
    /**
     * @var Aliases $aliases
     */
    $aliases = container(Aliases::class);
    return $aliases->get($path);
}

function aliases(string ...$paths): array
{
    /**
     * @var Aliases $aliases
     */
    $aliases = container(Aliases::class);
    return $aliases->getArray($paths);
}