<?php


namespace App\Controllers;


class HomeController extends Controller
{
    public function index(): array|string
    {
        return $this->view('home');
    }
}