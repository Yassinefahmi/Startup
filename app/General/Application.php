<?php


namespace App\General;



use App\Controllers\Controller;

class Application
{
    public static string $rootDirectory;

    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    private Controller $controller;

    public function __construct($rootPath)
    {
        self::$rootDirectory = $rootPath;

        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function getController(): Controller
    {
        return $this->controller;
    }

    public function issetController(): bool
    {
        return isset($this->controller);
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }
}