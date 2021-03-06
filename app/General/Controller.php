<?php


namespace App\General;


class Controller
{
    public function render($view, $params = []): array|string
    {
        return Application::$app->router->renderView($view, $params);
    }
}