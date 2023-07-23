<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Header;
use Yiisoft\Http\Status;

function response(
    int|null|string|array|StreamInterface $body,
    int $code = 200,
    string $status = 'OK',
    array $headers = []
): ResponseInterface {
    /**
     * @var DataResponseFactoryInterface $responseFactory
     */
    if ($responseFactory = container(DataResponseFactoryInterface::class, true)) {
        return $responseFactory->createResponse($body, $code, $status);
    }

    /**
     * @var ResponseFactoryInterface $responseFactory
     */
    $responseFactory = container(ResponseFactoryInterface::class);
    $response = $responseFactory
        ->createResponse(
            $code,
            $status
        );
    if ($headers) {
        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }
    }
    if ($body instanceof StreamInterface) {
        $response = $response->withBody($body);
    } else {
        $stream = $response->getBody();
        if (is_scalar($body)) {
            $stream->write((string) $body);
        } elseif (is_array($body)) {
            $stream->write(json_encode($body));
        }
    }
    return $response;
}


function redirect(
    string $name,
    array $parameters = [],
    array $query = [],
    int $code = Status::TEMPORARY_REDIRECT,
    bool $absolute = false
): ResponseInterface {
    /**
     * @var ResponseFactoryInterface $responseFactory
     */
    $responseFactory = container(ResponseFactoryInterface::class);

    if ($absolute) {
        if ($query) {
            $url = sprintf('%s?%s', $name, http_build_query($query));
        } else {
            $url = $name;
        }
    } else {
        $url = route($name, $parameters, $query);
    }

    return $responseFactory
        ->createResponse(
            $code,
            Status::TEXTS[$code] ?? Status::TEXTS[Status::TEMPORARY_REDIRECT]
        )
        ->withHeader(
            Header::LOCATION,
            $url
        );
}

