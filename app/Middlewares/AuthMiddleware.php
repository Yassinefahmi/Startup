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
    public function execute(): ForbiddenException
    {
        if (Application::isAuthenticated() === false
            && empty($this->actions)
            || in_array(Application::$app->getController()->getAction(), $this->actions)) {
            return throw new ForbiddenException();
        }
    }
}