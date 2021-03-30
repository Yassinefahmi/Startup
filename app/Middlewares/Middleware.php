<?php


namespace App\Middlewares;


use App\General\Application;
use JetBrains\PhpStorm\Pure;

abstract class Middleware
{
    /**
     * @var array|mixed
     */
    protected array $actions = [];

    /**
     * @var array
     */
    protected array $body;

    /**
     * Middleware constructor.
     * @param array $actions
     */
    #[Pure] public function __construct($actions = [])
    {
        $this->actions = $actions;
        $this->body = Application::$app->getRequest()->getBody();
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