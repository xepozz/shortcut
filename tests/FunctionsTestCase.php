<?php

declare(strict_types=1);

namespace Xepozz\YiiShort\Tests;

use PHPUnit\Framework\TestCase;

abstract class FunctionsTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        foreach ($this->bootstrapFiles() as $file) {
            require_once $file;
        }
    }

    abstract public function bootstrapFiles(): iterable;
}