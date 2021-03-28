<?php


namespace App\Controllers;


use App\Middlewares\AuthMiddleware;

class HomeController extends Controller
{
    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->registerMiddleware(new AuthMiddleware());
    }

    /**
     * Show the application dashboard.
     *
     * @return array|string
     */
    public function index(): array|string
    {
        return $this->view('home');
    }
}