<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;

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

