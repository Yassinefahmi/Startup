<?php


namespace App\Helpers;


use App\General\Application;
use JetBrains\PhpStorm\Pure;

class Request
{
    /**
     * Check whether the given method is currently used.
     *
     * @param string $method
     * @return bool
     */
    #[Pure] public static function isMethod(string $method): bool
    {
        return Application::$app->getRequest()->getMethod() === $method;
    }
}