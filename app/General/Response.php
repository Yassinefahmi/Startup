<?php


namespace App\General;


use JetBrains\PhpStorm\NoReturn;

class Response
{
    /**
     * Set a response code.
     *
     * @param int $code
     */
    public function setStatus(int $code): void
    {
        http_response_code($code);
    }

    /**
     * Redirect to given path.
     *
     * @param string $path
     */
    #[NoReturn] public function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}