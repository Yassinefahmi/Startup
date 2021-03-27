<?php


namespace App\Helpers;


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
}