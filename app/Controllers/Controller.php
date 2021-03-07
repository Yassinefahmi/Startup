<?php


namespace App\Controllers;


use App\General\Application;

class Controller
{
    public string $layout = 'main';

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function view($view, $params = []): array|string
    {
        return Application::$app->router->renderView($view, $params);
    }
}