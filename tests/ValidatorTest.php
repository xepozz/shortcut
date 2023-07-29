<?php

declare(strict_types=1);

namespace Xepozz\Shortcut\Tests;

use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\StreamFactory;
use Psr\Http\Message\StreamInterface;
use Xepozz\Shortcut\State;
use Xepozz\Shortcut\Tests\Support\IndexController;
use Yiisoft\Aliases\Aliases;
use Yiisoft\DataResponse\DataResponseFactory;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Test\Support\EventDispatcher\SimpleEventDispatcher;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Validator;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\View\ViewRenderer;

final class ValidatorTest extends FunctionsTestCase
{
    public function testFunctionExist()
    {
        $this->assertTrue(function_exists('validate'));
    }

    public function testViewWithController()
    {
        $this->initEnvironment();

        $result = validate(['parameter_name' => 'parameter_value'], ['parameter_name' => [new Required()]]);
        $this->assertInstanceOf(Result::class, $result);

        $this->assertTrue($result->isValid());
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/validator.php';
    }

    protected function initEnvironment(): void
    {
        State::$container = new SimpleContainer([
            ValidatorInterface::class => new Validator()
        ]);
    }
}