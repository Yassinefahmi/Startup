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
    private Database $database;
    private Migration $migration;
    private Controller $controller;

    public function __construct($rootPath)
    {
        self::$rootDirectory = $rootPath;

        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);

        $this->database = new Database();
        $this->migration = new Migration($this->database);
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    public function getMigration(): Migration
    {
        return $this->migration;
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