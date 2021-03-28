<?php


namespace App\Exceptions;


use Exception;

class NotFoundException extends Exception
{
    protected $message = 'Not found.';

    protected $code = 404;
}