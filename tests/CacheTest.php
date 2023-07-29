<?php

declare(strict_types=1);

namespace Xepozz\Shortcut\Tests;

use DateInterval;
use Psr\SimpleCache\CacheInterface;
use Xepozz\Shortcut\State;
use Xepozz\Shortcut\Tests\Support\StringableClass;
use Yiisoft\Cache\NullCache;
use Yiisoft\Test\Support\Container\SimpleContainer;

final class CacheTest extends FunctionsTestCase
{
    public function testFunctionExist()
    {
        $this->assertTrue(function_exists('cache'));
    }

    public function testCache(): void
    {
        $this->initEnvironment();

        $result = cache('key', 'value');
        $this->assertEquals('value', $result);


        $result = cache('key', 'value', 3600);
        $this->assertEquals('value', $result);

        $result = cache('key', 'value', new DateInterval('PT1H'));
        $this->assertEquals('value', $result);

        $result = cache('key', fn () => 'value');
        $this->assertEquals('value', $result);

        $result = cache(new StringableClass('key'), fn () => 'value');
        $this->assertEquals('value', $result);

        $result = cache(12345, fn () => 'value');
        $this->assertEquals('value', $result);

        $result = cache(['key' => 'value', '!@#$%^&*()_+`' => '_)(*&^%$#@!~`'], fn () => 'value');
        $this->assertEquals('value', $result);
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/cache.php';
    }

    protected function initEnvironment(): void
    {
        State::$container = new SimpleContainer([
            CacheInterface::class => new NullCache(),
        ]);
    }
}