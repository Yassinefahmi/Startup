<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Request;
use App\Helpers\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function index(): array|string
    {
        return $this->view('auth/register');
    }

    public function store(Request $request): array|string
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'min:6', 'confirmed']
        ]);

        if ($validated === false) {
            return $this->view('auth/register', $request->getErrors());
        }

        $user = new User();
        $user->registerColumns([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password'))
        ]);
        $user->save();

        return $this->view('auth/login');
    }
}