<?php


namespace App\Middlewares;


use App\General\Application;
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
    abstract protected function execute(): mixed;

    /**
     * Check whether the middleware is assigned to the performed action.
     *
     * @return mixed
     */
    public function handle(): mixed
    {
        if (empty($this->actions) || in_array(Application::$app->getController()->getAction(), $this->actions)) {
            return $this->execute();
        }

        return null;
    }
}