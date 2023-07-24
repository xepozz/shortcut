<?php

declare(strict_types=1);

use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\Auth\IdentityInterface;
use Yiisoft\User\CurrentUser;

function user(): CurrentUser
{
    /**
     * @var CurrentUser $user
     */
    $user = container(CurrentUser::class);

    return $user;
}

function can(
    string $permission,
    array $parameters = [],
    ?int $userId = null
): bool {
    if ($userId === null) {
        return user()->can($permission, $parameters);
    }
    /**
     * @var AccessCheckerInterface $accessChecker
     */
    $accessChecker = container(AccessCheckerInterface::class);
    return $accessChecker->userHasPermission($userId, $permission, $parameters);
}

function identity(): IdentityInterface
{
    return user()->getIdentity();
}
