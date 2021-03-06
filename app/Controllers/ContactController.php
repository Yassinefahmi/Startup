<?php


namespace App\Controllers;


use App\General\Controller;
use App\General\Request;

class ContactController extends Controller
{
    public function index(): string
    {
        $params = [
            'foo' => 'bar'
        ];

        return $this->render('contact', $params);
    }

    public function store(Request $request): string
    {
        $body = $request->getBody();

        return 'Handling submitted data';
    }
}