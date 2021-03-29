<?php


namespace App\Controllers;


use App\General\Application;
use App\General\Session;
use App\Middlewares\Middleware;
use JetBrains\PhpStorm\Pure;

class Controller
{
    /**
     * @var string
     */
    public string $layout = 'main';

    /**
     * @var string
     */
    private string $action;

    /**
     * @var Application
     */
    protected Application $app;

    /**
     * @var Session
     */
    protected Session $flashMessage;

    /**
     * @var Middleware[]
     */
    protected array $middlewares = [];

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
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
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

    /**
     * Register a middleware for the controller.
     *
     * @param Middleware $middleware
     */
    public function registerMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Register middlewares for the controller.
     *
     * @param array $middleware
     */
    public function registerMiddlewares(array $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * Get all registered middlewares of the controller.
     *
     * @return Middleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}