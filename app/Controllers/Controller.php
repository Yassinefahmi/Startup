<?php


namespace App\Controllers;


use App\General\Application;
use App\General\Session;
use JetBrains\PhpStorm\Pure;

class Controller
{
    public string $layout = 'main';
    protected Session $flashMessage;

    #[Pure] public function __construct()
    {
        $this->flashMessage = Application::$app->getSession();
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function view($view, $params = []): array|string
    {
        return Application::$app->router->renderView($view, $params);
    }
}