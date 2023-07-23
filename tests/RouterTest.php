<?php

declare(strict_types=1);

namespace Xepozz\YiiShort\Tests;

use Xepozz\YiiShort\State;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectionInterface;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Test\Support\Container\SimpleContainer;

class RouterTest extends FunctionsTestCase
{
    public function testContainer()
    {
        $this->assertTrue(function_exists('route'));
    }

    public function testGenerateRoute()
    {
        $routeCollector = new RouteCollector();
        $routeCollection = new RouteCollection(
            $routeCollector
                ->addRoute(Route::get('/test')->name('test'))
                ->addRoute(Route::get('/test/{id}')->name('test-id'))
        );
        State::$container = new SimpleContainer([
            RouteCollectionInterface::class => $routeCollection,
            UrlGeneratorInterface::class => new UrlGenerator($routeCollection),
        ]);

        $url = route('test');
        $this->assertEquals('/test', $url);

        $url = route('test-id', ['id' => 123]);
        $this->assertEquals('/test/123', $url);

        $url = route('test', query: ['id' => 123]);
        $this->assertEquals('/test?id=123', $url);
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
    }
}