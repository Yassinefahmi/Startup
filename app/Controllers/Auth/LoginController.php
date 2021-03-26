<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Request;
use App\Helpers\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function index(): array|string
    {
        return $this->view('auth/login');
    }

    public function authenticate(Request $request): array|string
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if ($validated === false) {
            return $this->view('auth/login', $request->getErrors());
        }

        $user = User::findWhere([
            'username' => $request->input('username')
        ]);

        if ($user === null) {
            $this->flashMessage->setFlashMessage('danger', 'This username does not exist!');

            return $this->view('auth/login');
        }

        if (Hash::verify($request->input('password'), $user->getAttributeValue('password')) === false) {
          $this->flashMessage->setFlashMessage('danger', 'The user credentials were incorrect!');

          return $this->view('auth/login');
        }

        $this->app->authenticateUser($user);

        return $this->view('home');
    }
}