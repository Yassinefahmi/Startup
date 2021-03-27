<?php


namespace App\Controllers;


use App\General\Application;
use App\General\Session;
use JetBrains\PhpStorm\Pure;

class Controller
{
    /**
     * @var string
     */
    public string $layout = 'main';

    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @var Session
     */
    protected Session $flashMessage;

    /**
     * Controller constructor.
     */
    #[Pure] public function __construct()
    {
        $this->app = Application::$app;
        $this->flashMessage = Application::$app->getSession();
    }

    /**
     * Set the default layout.
     *
     * @param string $layout
     * @return void
     */
    public function setLayout(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Get the given view and seed it with any parameters.
     *
     * @param $view
     * @param array $params
     * @return array|string
     */
    public function view($view, $params = []): array|string
    {
        return Application::$app->getRouter()->renderView($view, $params);
    }
}