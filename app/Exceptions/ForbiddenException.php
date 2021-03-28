<?php


namespace App\Exceptions;


use Exception;

class ForbiddenException extends Exception
{
    protected $message = 'Sorry, you are not authorized to perform this action.';

    protected $code = 403;

}