<?php

declare(strict_types=1);

namespace Xepozz\YiiShort\Tests;

use Xepozz\YiiShort\State;
use Yiisoft\Test\Support\Container\SimpleContainer;

class ContainerTest extends FunctionsTestCase
{
    public function testFunctionLoaded()
    {
        $this->assertTrue(function_exists('container'));
    }

    public function testContainerIsUnset()
    {
        State::$container = null;

        $this->expectException(\RuntimeException::class);
        container('test', true);
    }

    public function testContainerGetOptional()
    {
        State::$container = new SimpleContainer();
        $this->assertNull(container('test', true));
    }

    public function testContainerGetNotOptional()
    {
        State::$container = new SimpleContainer();
        $this->expectException(\Psr\Container\NotFoundExceptionInterface::class);
        $this->assertNull(container('test'));
    }

    public function testContainerGet()
    {
        State::$container = new SimpleContainer([
            'test' => 'test',
        ]);
        $result = container('test', true);
        $this->assertEquals('test', $result);
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
    }
}