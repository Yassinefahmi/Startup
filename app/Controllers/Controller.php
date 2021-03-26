<?php


namespace App\Controllers;


use App\General\Application;
use App\General\Session;
use JetBrains\PhpStorm\Pure;

class Controller
{
    public string $layout = 'main';

    protected Application $app;
    protected Session $flashMessage;

    #[Pure] public function __construct()
    {
        $this->app = Application::$app;
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