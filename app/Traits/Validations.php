<?php


namespace App\Traits;


use JetBrains\PhpStorm\Pure;

trait Validations
{
    public function required($value): bool
    {
        if ($value === null || empty($value)) {
            return false;
        }

        return true;
    }

    #[Pure] public function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    #[Pure] public function min($value, string $amount): bool
    {
        if (is_string($value) && strlen($value) < $amount) {
            return false;
        }

        if (is_int($value) && $value < $amount) {
            return false;
        }

        if (is_array($value) && count($value) < $amount) {
            return false;
        }

        return true;
    }

    #[Pure] public function max($value, string $amount): bool
    {
        if (is_string($value) && strlen($value) > $amount) {
            return false;
        }

        if (is_int($value) && $value > $amount) {
            return false;
        }

        if (is_array($value) && count($value) > $amount) {
            return false;
        }

        return true;
    }

    #[Pure] public function confirmed($password, $passwordConfirmed): bool
    {
        return strcmp($password, $passwordConfirmed) == 0;
    }
}