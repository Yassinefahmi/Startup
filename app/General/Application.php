<?php


namespace App\General;



use App\Controllers\Controller;
use App\Models\Model;
use App\Models\User;

class Application
{
    public static string $rootDirectory;

    public Router $router;
    public Request $request;
    public Response $response;

    public static Application $app;

    private Session $session;
    private Database $database;
    private Migration $migration;
    private Controller $controller;

    private ?User $user = null;
    private string $userModel;

    public function __construct($rootPath, array $config)
    {
        self::$rootDirectory = $rootPath;

        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->database = new Database();
        $this->migration = new Migration($this->database);

        $this->userModel = $config['userModel'];

        $primaryValue = $this->session->get('user');

        if ($primaryValue) {
            $primaryKey = $this->userModel::primaryKey();
            $this->user = $this->userModel::findWhere([
                $primaryKey => $primaryValue
            ]);
        }
    }

    public function getSession(): Session
    {
        return $this->session;
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

    public static function isAuthenticated(): bool
    {
        return self::$app->user !== null;
    }

    public function getAuthenticatedUser(): ?Model
    {
        return $this->user;
    }

    public function authenticateUser(User $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
    }

    public function logoutUser()
    {
        $this->user = null;
        $this->session->remove('user');
    }
}