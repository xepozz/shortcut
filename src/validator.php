<?php

declare(strict_types=1);

use Yiisoft\Validator\Result;
use Yiisoft\Validator\ValidationContext;
use Yiisoft\Validator\ValidatorInterface;

function validate(
    mixed $data,
    callable|iterable|object|string|null $rules = null,
    ?ValidationContext $context = null,
): Result {
    /**
     * @var ValidatorInterface $validator
     */
    $validator = container(ValidatorInterface::class);
    return $validator->validate(
        $data,
        $rules,
        $context,
    );
}
