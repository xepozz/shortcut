<?php

declare(strict_types=1);

namespace Xepozz\YiiShort\Tests;

use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\StreamFactory;
use Psr\Http\Message\StreamInterface;
use Xepozz\YiiShort\State;
use Xepozz\YiiShort\Tests\Support\IndexController;
use Yiisoft\Aliases\Aliases;
use Yiisoft\DataResponse\DataResponseFactory;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\ViewRenderer;

class ViewTest extends FunctionsTestCase
{
    public function testFunctionExist()
    {
        $this->assertTrue(function_exists('view'));
    }

    public function testViewWithController()
    {
        $this->initEnvironment();

        $response = view('index', ['parameter_name' => 'parameter_value'], new IndexController());

        $body = $response->getBody();
        $this->assertInstanceOf(StreamInterface::class, $body);
        $body->rewind();
        $content = $body->getContents();

        $this->assertEquals('var $parameter_name = parameter_value', $content);
    }

    public function testViewWithoutController()
    {
        $this->initEnvironment();

        $response = view('my_view', ['parameter_name' => 'parameter_value']);

        $body = $response->getBody();
        $this->assertInstanceOf(StreamInterface::class, $body);
        $body->rewind();
        $content = $body->getContents();

        $this->assertEquals('var $parameter_name = parameter_value', $content);
    }

    public function testRenderProxyUnknownView()
    {
        $this->initEnvironment();

        view('unknown-2view');
        $this->expectNotToPerformAssertions();
    }

    public function testRenderUnpackingProxyUnknownViewFails()
    {
        $this->initEnvironment();

        $response = view('unknown-2view');
        $this->expectException(\Yiisoft\View\Exception\ViewNotFoundException::class);
        $response->getBody();
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/view.php';
    }

    protected function initEnvironment(): void
    {
        $dataResponseFactory = new DataResponseFactory(
            new ResponseFactory(),
            new StreamFactory(),
        );
        State::$container = new SimpleContainer([
            ViewRenderer::class => new ViewRenderer(
                $dataResponseFactory,
                new Aliases([]),
                new WebView(__DIR__ . '/Support/views', new SimpleEventDispatcher()),
                __DIR__ . '/Support/views',
            ),
        ]);
    }
}