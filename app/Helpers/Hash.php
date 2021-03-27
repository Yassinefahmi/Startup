<?php


namespace App\Helpers;


class Hash
{
    /**
     * Hash the given password.
     *
     * @param string $password
     * @return bool|string|null
     */
    public static function make(string $password): bool|string|null
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify whether the passwords are equal.
     *
     * @param string $password
     * @param string $hashedPassword
     * @return bool
     */
    public static function verify(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}