<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Request;

class RegisterController extends Controller
{
    public function index(): array|string
    {
        return $this->view('auth/register');
    }

    public function store(Request $request): array|string
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3'],
            'password' => ['required', 'string', 'confirmed']
        ]);

        var_dump($request->getErrors());

        return $this->view('auth/register');
    }
}