<?php


namespace App\Helpers;


use App\Exceptions\NotFoundException;
use App\General\Application;
use JetBrains\PhpStorm\Pure;

class Route
{
    /**
     * Get the current path.
     *
     * @return string
     */
    #[Pure] public static function getCurrentPath(): string
    {
        return Application::$app->getRequest()->getPath();
    }

    /**
     * Get URI of given route name.
     *
     * @param string $name
     * @return int|string|null
     */
    public static function name(string $name): int|string|null
    {
        try {
            return Application::$app->getRouter()->formatURI($name);
        } catch (NotFoundException $e) {
            return $e->getMessage();
        }
    }
}