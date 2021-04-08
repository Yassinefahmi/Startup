<?php


namespace App\Middlewares;


use App\Exceptions\InvalidCsrfTokenException;
use App\General\Application;

class CsrfTokenMiddleware extends Middleware
{
    /**
     * @return mixed
     * @throws InvalidCsrfTokenException
     */
    protected function execute(): mixed
    {
        if (isset($this->body['csrf']) === false
            || Application::$app->getSession()->isCsrfExpired()
            || Application::$app->getSession()->get('csrf') !== $this->body['csrf']) {
            return throw new InvalidCsrfTokenException();
        }

        return null;
    }
}