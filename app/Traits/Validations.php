<?php


namespace App\Traits;


trait Validations
{
    public function required($value): bool
    {
        if ($value === null || empty($value)) {
            return false;
        }

        return true;
    }

    public function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function min($value, string $amount): bool
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

    public function max($value, string $amount): bool
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

    public function confirmed($password, $passwordConfirmed)
    {
        return strcmp($password, $passwordConfirmed) == 0;
    }
}