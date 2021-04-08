<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Application;
use App\General\Request;
use App\Helpers\Hash;
use App\Models\User;
use JetBrains\PhpStorm\NoReturn;

class RegisterController extends Controller
{
    public function index(): array|string
    {
        if (Application::isAuthenticated()) {
            $this->redirect('home');
        }

        return $this->view('auth/register');
    }

    /**
     * Creates a user.
     *
     * @param Request $request
     */
    #[NoReturn] public function store(Request $request): void
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        if ($validated === false) {
            $this->redirect('register');
        }

        $unique = User::findOneWhere([
            'username' => $request->input('username')
        ]);

        if ($unique !== null) {
            $this->flashMessage->setFlashMessage('danger', 'This username is already being used.');

            $this->redirect('register');
        }

        $user = new User();
        $user->registerColumns([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password'))
        ]);
        $user->save();

        $this->flashMessage->setFlashMessage('success', 'The user has successfully been registered.');

        $this->redirect('register');
    }
}