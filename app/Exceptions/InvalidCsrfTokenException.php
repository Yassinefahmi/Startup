<?php


namespace App\Exceptions;


use Exception;

class InvalidCsrfTokenException extends Exception
{
    protected $message = 'Page expired.';

    protected $code = 419;
}