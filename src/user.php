<?php

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Xepozz\YiiShort\State;
use Yiisoft\Access\AccessCheckerInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\ViewRenderer;

function can(
    string $permission,
    array $parameters = [],
    ?int $userId = null
): bool {
    if ($userId === null) {
        /**
         * @var CurrentUser $user
         */
        $user = container(CurrentUser::class);
        return $user->can($permission, $parameters);
    }
    /**
     * @var AccessCheckerInterface $accessChecker
     */
    $accessChecker = container(AccessCheckerInterface::class);
    return $accessChecker->userHasPermission($userId, $permission, $parameters);
}
