<?php


namespace App\General;


use App\Exceptions\NotFoundException;
use App\Middlewares\CsrfTokenMiddleware;
use Closure;

class Router
{
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var Response
     */
    private Response $response;
    /**
     * @var array
     */
    protected array $routes = [];

    /**
     * Router constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Register a GET route.
     *
     * @param string $path
     * @param string|array|Closure $callback
     * @param string|null $name
     */
    public function get(string $path, string|array|Closure $callback, string $name = null): void
    {
        $this->routes['get'][$path][$name] = $callback;
    }

    /**
     * Register a POST route.
     *
     * @param string $path
     * @param string|array|Closure $callback
     * @param string|null $name
     */
    public function post(string $path, string|array|Closure $callback, string $name = null): void
    {
        $this->routes['post'][$path][$name] = $callback;
    }

    /**
     * Redirect to another URI.
     *
     * @param string $path
     * @param string $destinationPath
     * @param int $responseCode
     */
    public function redirect(string $path, string $destinationPath, int $responseCode = 200): void
    {
        if ($this->request->getPath() === $path) {
            $this->response->setStatus($responseCode);
            $this->response->redirect($destinationPath);
        }
    }

    /**
     * Get URI of given route name.
     *
     * @param string $name
     * @return int|string|null
     * @throws NotFoundException
     */
    public function formatURI(string $name): int|string|null
    {
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $path => $routeName) {
                if (key($routeName) === $name) {
                    $environment = new Environment();
                    return $environment->getApplicationVariables()['APP_URL'] . $path;
                }
            }
        }

        return throw new NotFoundException();
    }

    /**
     * Render functionalities if route does or not exist.
     *
     * @return mixed
     * @throws NotFoundException
     */
    public function resolve(): mixed
    {
        $method = $this->request->getMethod();
        $path = $this->request->getPath();

        $name = $this->routes[$method][$path] ?? false;
        $callback = $name ? current($name) : false;

        if ($method !== 'get') {
            $csrf = new CsrfTokenMiddleware();
            $csrf->handle();
        }

        if ($callback === false) {
            $this->response->setStatus(404);

            return throw new NotFoundException();
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
            Application::$app->setController($callback[0]);

            $controller = Application::$app->getController();
            $controller->setAction($callback[1]);

            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->handle();
            }
        }

        return call_user_func($callback, $this->request);
    }

    /**
     * Render the given view and seed it with parameters.
     *
     * @param $view
     * @param array $params
     * @return array|string
     */
    public function renderView($view, $params = []): array|string
    {
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContents = $this->layoutContent();

        return str_replace('{{ content }}', $viewContent, $layoutContents);
    }

    /**
     * Replace {{ content }} with actually content of a view.
     *
     * @param $viewContent
     * @return array|bool|string
     */
    private function renderContent($viewContent): array|bool|string
    {
        $layoutContents = $this->layoutContent();

        return str_replace('{{ content }}', $viewContent, $layoutContents);
    }

    /**
     * Render the layout.
     *
     * @return bool|string
     */
    protected function layoutContent(): bool|string
    {
        $app = Application::$app;

        $layout = $app->issetController() ? $app->getController()->layout : 'main';

        ob_start();

        require_once Application::$rootDirectory . "/views/layouts/$layout.php";

        return ob_get_clean();
    }

    /**
     * Render the given view and seed it with parameters.
     *
     * @param $view
     * @param $params
     * @return bool|string
     */
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