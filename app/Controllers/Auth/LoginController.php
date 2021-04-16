<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Application;
use App\General\Request;
use App\Helpers\Hash;
use App\Middlewares\AuthMiddleware;
use App\Models\User;
use JetBrains\PhpStorm\NoReturn;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware([
            'logout'
        ]));
    }

    public function index(): array|string
    {
        if (Application::isAuthenticated()) {
            $this->redirect('home');
        }

        return $this->view('auth/login');
    }

    #[NoReturn] public function authenticate(Request $request): void
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($validated === false) {
            $this->redirect('login');
        }

        $user = User::findOneWhere([
            'username' => $request->input('username')
        ]);

        if ($user === false) {
            $this->flashMessage->setFlashMessage('danger', 'This username does not exist!');

            $this->redirect('login');
        }

        if (Hash::verify($request->input('password'), $user->getAttributeValue('password')) === false) {
          $this->flashMessage->setFlashMessage('danger', 'The user credentials were incorrect!');

          $this->redirect('login');
        }

        $this->app->authenticateUser($user);

        $this->redirect('home');
    }

    #[NoReturn] public function logout(): void
    {
        Application::$app->logoutUser();

        var_dump('sd');
        $this->redirect('login');
    }
}