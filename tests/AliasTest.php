<?php

declare(strict_types=1);

namespace Xepozz\Shortcut\Tests;

use Xepozz\Shortcut\State;
use Yiisoft\Aliases\Aliases;
use Yiisoft\Test\Support\Container\SimpleContainer;

class AliasTest extends FunctionsTestCase
{
    public function testFunctionLoaded(): void
    {
        $this->assertTrue(function_exists('alias'));
        $this->assertTrue(function_exists('aliases'));
    }

    public function testContainerIsUnset(): void
    {
        State::$container = null;

        $this->expectException(\RuntimeException::class);
        alias('test');
    }

    public function testAliases(): void
    {
        State::$container = new SimpleContainer([
            Aliases::class => new Aliases([
                '@test1' => '/root/test1',
                '@test2' => '/root/test2',
            ]),
        ]);
        $this->assertEquals('/root/test1', alias('@test1'));
        $this->assertEquals('/root/test2', alias('@test2'));
        $this->assertEquals(['/root/test1', '/root/test2'], aliases('@test1', '@test2'));
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/alias.php';
    }
}