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

    public function post($path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if ($callback === false) {
            $this->response->setStatus(404);
            return $this->renderView('responses/404');
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = []): array|string
    {
        $layoutContents = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);

        return str_replace('{{ content }}', $viewContent, $layoutContents);
    }

    private function renderContent($viewContent): array|bool|string
    {
        $layoutContents = $this->layoutContent();

        return str_replace('{{ content }}', $viewContent, $layoutContents);
    }

    protected function layoutContent(): bool|string
    {
        ob_start();
        require_once Application::$rootDirectory . "/views/layouts/main.php";

        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params): bool|string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        require_once Application::$rootDirectory . "/views/$view.php";

        return ob_get_clean();
    }
}