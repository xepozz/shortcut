<?php

declare(strict_types=1);

namespace Xepozz\YiiShort\Tests;

use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\Stream;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseFactoryInterface;
use Xepozz\YiiShort\State;
use Yiisoft\Test\Support\Container\SimpleContainer;

class ResponseTest extends FunctionsTestCase
{
    public function testContainer()
    {
        $this->assertTrue(function_exists('response'));
    }

    public function testContainerIsUnset()
    {
        State::$container = null;

        $this->expectException(\RuntimeException::class);
        response('test');
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

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/response.php';
    }
}