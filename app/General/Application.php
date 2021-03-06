<?php


namespace App\General;



class Application
{
    public static string $rootDirectory;

    public Router $router;
    public Request $request;
    public static Application $app;

    public function __construct($rootPath)
    {
        self::$rootDirectory = $rootPath;

        self::$app = $this;

        $this->request = new Request();
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }
}