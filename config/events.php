<?php

use Psr\Container\ContainerInterface;
use Xepozz\YiiShort\State;
use Yiisoft\Yii\Http\Event\ApplicationStartup;

return [
    ApplicationStartup::class => [
        static fn (ContainerInterface $container) => State::$container = $container,
    ],
];