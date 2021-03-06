<?php


namespace App\Controllers;


use App\General\Controller;

class ContactController extends Controller
{
    public function index(): string
    {
        $params = [
            'foo' => 'bar'
        ];

        return $this->render('contact', $params);
    }

    public function store(): string
    {
        return 'Handling submitted data';
    }
}