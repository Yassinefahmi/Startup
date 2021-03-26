<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Request;
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
            $this->view('auth/login', $request->getErrors());
        }

        echo "<pre>";
        var_dump(User::findWhere(['username' => $request->input('username')]));
        echo "</pre>";

        return $this->view('auth/login');
    }
}