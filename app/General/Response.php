<?php


namespace App\General;


class Response
{
    public function setStatus(int $code)
    {
        http_response_code($code);
    }

    public function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}