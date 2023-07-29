<?php

declare(strict_types=1);

namespace Xepozz\Shortcut\Tests\Support;

use Stringable;

final class StringableClass implements Stringable
{
    public function __construct(private string $string)
    {
    }

    public function __toString()
    {
        return $this->string;
    }
}