<?php


namespace App\General;



use App\Controllers\Controller;
use App\Models\Model;
use App\Models\User;

class Application
{
    /**
     * @var string
     */
    public static string $rootDirectory;
    /**
     * @var Application
     */
    public static Application $app;
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var Response
     */
    private Response $response;
    /**
     * @var Session
     */
    private Session $session;
    /**
     * @var Database
     */
    private Database $database;
    /**
     * @var Migration
     */
    private Migration $migration;
    /**
     * @var Controller
     */
    private Controller $controller;
    /**
     * @var User|null
     */
    private ?User $user = null;
    /**
     * @var string|mixed
     */
    private string $userModel;

    /**
     * Application constructor.
     * @param $rootPath
     * @param array $config
     */
    public function __construct($rootPath, array $config = [])
    {
        self::$rootDirectory = $rootPath;

        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->session = new Session();
        $this->database = new Database();
        $this->migration = new Migration($this->database);

        $this->userModel = empty($config) ? Model::class : $config['userModel'];

        $primaryValue = $this->session->get('user');

        if ($primaryValue) {
            $primaryKey = (new $this->userModel)->primaryKey();
            $this->user = $this->userModel::findOneWhere([
                $primaryKey => $primaryValue
            ]);
        }
    }

    /**
     * Get the initialized router.
     *
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Get the initialized request.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Get the initialized session.
     *
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * Get the initialized database.
     *
     * @return Database
     */
    public function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * Get the initialized migration.
     *
     * @return Migration
     */
    public function getMigration(): Migration
    {
        return $this->migration;
    }

    /**
     * Get the configured controller.
     *
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * Configure the controller.
     *
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * Check whether the controller has configured.
     *
     * @return bool
     */
    public function issetController(): bool
    {
        return isset($this->controller);
    }

    /**
     * Check whether a user is authenticated.
     *
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        return self::$app->user !== null;
    }

    /**
     * Get the authenticated user.
     *
     * @return Model|null
     */
    public function getAuthenticatedUser(): ?Model
    {
        return $this->user;
    }

    /**
     * Authenticate the given user.
     *
     * @param User $user
     */
    public function authenticateUser(User $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
    }

    /**
     * Remove session of the authenticated user.
     *
     * @return void
     */
    public function logoutUser(): void
    {
        $this->user = null;
        $this->session->remove('user');
    }
}