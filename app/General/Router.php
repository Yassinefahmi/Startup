<?php


namespace App\General;


class Router
{
    public Request $request;
    public Response $response;

    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatus(404);
            return "This view can not be found.";
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        return call_user_func($callback);
    }

    private function renderView($view)
    {
        $layoutContents = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);

        return str_replace('{{ content }}', $viewContent, $layoutContents);

        require_once Application::$rootDirectory . "/views/$view.php";
    }

    protected function layoutContent()
    {
        ob_start();

        require_once Application::$rootDirectory . "/views/layouts/main.php";

        return ob_get_clean();
    }

    protected function renderOnlyView($view)
    {
        ob_start();

        require_once Application::$rootDirectory . "/views/$view.php";

        return ob_get_clean();
    }
}