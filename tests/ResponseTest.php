<?php

declare(strict_types=1);

namespace Xepozz\Shortcut\Tests;

use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\Stream;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Xepozz\Shortcut\State;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;
use Yiisoft\Router\FastRoute\UrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectionInterface;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Test\Support\Container\SimpleContainer;

class ResponseTest extends FunctionsTestCase
{
    public function testFunctionLoaded()
    {
        $this->assertTrue(function_exists('response'));
        $this->assertTrue(function_exists('redirect'));
    }

    #[DataProvider('dataContainerIsUnset')]
    public function testContainerIsUnset(callable $callback)
    {
        State::$container = null;

        $this->expectException(\RuntimeException::class);
        $callback();
    }

    public static function dataContainerIsUnset(): iterable
    {
        yield 'response' => [fn () => response('test')];
        yield 'redirect' => [fn () => redirect('name')];
    }

    #[DataProvider('dataResponseBody')]
    public function testResponseBody(mixed $body, string $expectedBody)
    {
        State::$container = new SimpleContainer([
            ResponseFactoryInterface::class => new ResponseFactory(),
        ]);

        $response = response($body);
        $body = $response->getBody();
        $body->rewind();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedBody, $body->getContents());
    }

    public static function dataResponseBody(): iterable
    {
        yield 'string' => ['test', 'test'];
        yield 'null' => [null, ''];
        yield 'int' => [123, '123'];

        $stream = fopen('php://temp', 'w+');
        fwrite($stream, 'test');

        yield 'stream' => [new Stream($stream), 'test'];

        yield 'array' => [[['key' => 'value'], 1], '[{"key":"value"},1]'];
    }

    public function testResponseStatusCode()
    {
        State::$container = new SimpleContainer([
            ResponseFactoryInterface::class => new ResponseFactory(),
        ]);

        $response = response('test', 500);
        $body = $response->getBody();
        $body->rewind();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('test', $body->getContents());
    }

    public function testResponseStatusText()
    {
        State::$container = new SimpleContainer([
            ResponseFactoryInterface::class => new ResponseFactory(),
        ]);

        $response = response('test', 200, 'My own status text');
        $body = $response->getBody();
        $body->rewind();
        $this->assertEquals('My own status text', $response->getReasonPhrase());
        $this->assertEquals('test', $body->getContents());
    }

    public function testResponseHeaders()
    {
        State::$container = new SimpleContainer([
            ResponseFactoryInterface::class => new ResponseFactory(),
        ]);

        $response = response(
            'test',
            headers: ['X-Test' => 'test']
        );
        $body = $response->getBody();
        $body->rewind();
        $this->assertEquals('test', $response->getHeaderLine('X-Test'));
        $this->assertEquals('test', $body->getContents());
    }

    public function testRedirectRoute()
    {
        $routeCollector = new RouteCollector();
        $routeCollection = new RouteCollection(
            $routeCollector
                ->addRoute(Route::get('/redirect')->name('test'))
                ->addRoute(Route::get('/redirect/{id}')->name('test-id'))
        );
        State::$container = new SimpleContainer([
            ResponseFactoryInterface::class => new ResponseFactory(),
            RouteCollectionInterface::class => $routeCollection,
            UrlGeneratorInterface::class => new UrlGenerator($routeCollection),
        ]);

        $response = redirect('test');
        $this->assertEquals(Status::TEMPORARY_REDIRECT, $response->getStatusCode());
        $this->assertEquals('/redirect', $response->getHeaderLine(Header::LOCATION));

        $response = redirect('test-id', ['id' => 123]);
        $this->assertEquals(Status::TEMPORARY_REDIRECT, $response->getStatusCode());
        $this->assertEquals('/redirect/123', $response->getHeaderLine(Header::LOCATION));

        $response = redirect('test-id', ['id' => 123], ['k' => 'v']);
        $this->assertEquals(Status::TEMPORARY_REDIRECT, $response->getStatusCode());
        $this->assertEquals('/redirect/123?k=v', $response->getHeaderLine(Header::LOCATION));
    }

    public function testRedirectAbsolute()
    {
        State::$container = new SimpleContainer([
            ResponseFactoryInterface::class => new ResponseFactory(),
        ]);

        $response = redirect('/path/to/redirect', [], [], Status::TEMPORARY_REDIRECT, true);
        $this->assertEquals(Status::TEMPORARY_REDIRECT, $response->getStatusCode());
        $this->assertEquals('/path/to/redirect', $response->getHeaderLine(Header::LOCATION));

        $response = redirect('/path/to/redirect', [], ['k' => 'v'], Status::TEMPORARY_REDIRECT, true);
        $this->assertEquals(Status::TEMPORARY_REDIRECT, $response->getStatusCode());
        $this->assertEquals('/path/to/redirect?k=v', $response->getHeaderLine(Header::LOCATION));
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/router.php';
        yield __DIR__ . '/../src/response.php';
    }
}