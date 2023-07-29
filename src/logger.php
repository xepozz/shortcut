<?php

declare(strict_types=1);

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

function log_error(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::ERROR, $message, $context);
}

function log_info(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::INFO, $message, $context);
}

function log_warning(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::WARNING, $message, $context);
}

function log_debug(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::DEBUG, $message, $context);
}

function log_notice(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::NOTICE, $message, $context);
}

function log_critical(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::CRITICAL, $message, $context);
}

function log_emergency(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::EMERGENCY, $message, $context);
}

function log_alert(string|Stringable $message, array $context = []): void
{
    log_message(LogLevel::ALERT, $message, $context);
}

function log_message(
    string $level,
    string|stringable $message,
    array $context = [],
): void {
    /**
     * @var LoggerInterface $logger
     */
    $logger = container(LoggerInterface::class);
    $logger->log($level, $message, $context);
}
