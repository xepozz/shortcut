<?php

declare(strict_types=1);

use Yiisoft\DataResponse\DataResponse;
use Yiisoft\Yii\View\ViewRenderer;

function view(
    string $view,
    array $parameters = [],
    ?object $controller = null
): DataResponse {
    /**
     * @var ViewRenderer $renderer
     */
    $renderer = container(ViewRenderer::class);
    if ($controller !== null) {
        $renderer = $renderer->withController($controller);
    }
    return $renderer->render($view, $parameters);
}
