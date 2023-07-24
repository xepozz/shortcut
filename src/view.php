<?php

declare(strict_types=1);

use Yiisoft\DataResponse\DataResponse;
use Yiisoft\Yii\View\ViewRenderer;

function view(
    string $view,
    array $parameters = [],
    null|string|object $controller = null
): DataResponse {
    /**
     * @var ViewRenderer $renderer
     */
    $renderer = container(ViewRenderer::class);
    if (is_object($controller)) {
        $renderer = $renderer->withController($controller);
    } elseif(is_string($controller)) {
        $renderer = $renderer->withControllerName($controller);
    }
    return $renderer->render($view, $parameters);
}
