<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/app.php';

/** @var $config array */

use App\General\Application;

$app = new Application(dirname(__DIR__), $config);

/**
 * Load all initialized routes.
 */

require_once __DIR__ . '/../routes/web.php';

try {
    echo $app->getRouter()->resolve();
} catch (Exception $exception) {
    echo $app->getRouter()->renderView('_error', [
        'exception' => $exception
    ]);
}