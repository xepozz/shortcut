<?php

declare(strict_types=1);

use Yiisoft\Translator\TranslatorInterface;

function t(
    string $message,
    array $parameters = [],
    string $category = 'app',
    string $locale = null
): string {
    /**
     * @var TranslatorInterface $translator
     */
    $translator = container(TranslatorInterface::class);

    return $translator->translate(
        $message,
        $parameters,
        $category,
        $locale,
    );
}
