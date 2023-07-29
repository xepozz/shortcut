<?php

declare(strict_types=1);

namespace Xepozz\Shortcut\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Stringable;
use Xepozz\Shortcut\State;
use Xepozz\Shortcut\Tests\Support\StringableClass;
use Yiisoft\Test\Support\Container\SimpleContainer;

final class LoggerTest extends FunctionsTestCase
{
    public function testFunctionExist()
    {
        $this->assertTrue(function_exists('log_error'));
        $this->assertTrue(function_exists('log_info'));
        $this->assertTrue(function_exists('log_warning'));
        $this->assertTrue(function_exists('log_debug'));
        $this->assertTrue(function_exists('log_notice'));
        $this->assertTrue(function_exists('log_critical'));
        $this->assertTrue(function_exists('log_emergency'));
        $this->assertTrue(function_exists('log_alert'));
        $this->assertTrue(function_exists('log_message'));
    }

    public function testLogMessage(): void
    {
        $this->initEnvironment();
        $this->expectNotToPerformAssertions();

        log_message('error', 'test message', ['some context']);
    }

    #[DataProvider('dataLogFunctions')]
    public function testLogFunctions(string $function, string|Stringable $message, array $context): void
    {
        $this->initEnvironment();
        $this->expectNotToPerformAssertions();

        $function($message, $context);
    }

    public static function dataLogFunctions(): iterable
    {
        yield 'log_error' => ['log_error', 'message', []];
        yield 'log_error' => ['log_error', new StringableClass('message'), ['key' => 'value']];

        yield 'log_info' => ['log_info', 'message', []];
        yield 'log_info' => ['log_info', new StringableClass('message'), ['key' => 'value']];

        yield 'log_warning' => ['log_warning', 'message', []];
        yield 'log_warning' => ['log_warning', new StringableClass('message'), ['key' => 'value']];

        yield 'log_debug' => ['log_debug', 'message', []];
        yield 'log_debug' => ['log_debug', new StringableClass('message'), ['key' => 'value']];

        yield 'log_notice' => ['log_notice', 'message', []];
        yield 'log_notice' => ['log_notice', new StringableClass('message'), ['key' => 'value']];

        yield 'log_alert' => ['log_alert', 'message', []];
        yield 'log_alert' => ['log_alert', new StringableClass('message'), ['key' => 'value']];

        yield 'log_critical' => ['log_critical', 'message', []];
        yield 'log_critical' => ['log_critical', new StringableClass('message'), ['key' => 'value']];

        yield 'log_emergency' => ['log_emergency', 'message', []];
        yield 'log_emergency' => ['log_emergency', new StringableClass('message'), ['key' => 'value']];
    }

    public function bootstrapFiles(): iterable
    {
        yield __DIR__ . '/../src/container.php';
        yield __DIR__ . '/../src/logger.php';
    }

    protected function initEnvironment(): void
    {
        State::$container = new SimpleContainer([
            LoggerInterface::class => new NullLogger(),
        ]);
    }
}