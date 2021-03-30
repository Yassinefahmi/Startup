<?php


namespace App\Middlewares;


use App\Exceptions\ForbiddenException;
use App\General\Application;

class AuthMiddleware extends Middleware
{
    /**
     * @return mixed
     * @throws ForbiddenException
     */
    protected function execute(): mixed
    {
        if (Application::isAuthenticated() === false) {
            return throw new ForbiddenException();
        }

        return null;
    }
}