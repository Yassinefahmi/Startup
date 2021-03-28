<?php


namespace App\Middlewares;


use Exception;

abstract class Middleware
{
    protected array $actions = [];

    public function __construct($actions = [])
    {
        $this->actions = $actions;
    }

    /**
     * Validate and throw an exception.
     *
     * @return mixed
     */
    abstract public function execute(): mixed;
}