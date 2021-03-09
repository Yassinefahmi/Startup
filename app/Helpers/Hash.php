<?php


namespace App\Helpers;


class Hash
{
    public static function make(string $password): bool|string|null
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public static function check(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}