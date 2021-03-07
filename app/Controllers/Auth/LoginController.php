<?php


namespace App\Controllers\Auth;


use App\Controllers\Controller;
use App\General\Request;

class LoginController extends Controller
{
    public function index(): array|string
    {
        return $this->view('auth/login');
    }

    public function authenticate(Request $request): array|string
    {
        return $this->view('home');
    }
}