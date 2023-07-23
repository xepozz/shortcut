<?php

declare(strict_types=1);


use Yiisoft\Router\UrlGeneratorInterface;

function route(
    string $name,
    array $parameters = [],
    array $query = [],
): string {
    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    $urlGenerator = container(UrlGeneratorInterface::class);
    return $urlGenerator->generate($name, $parameters, $query);
}

