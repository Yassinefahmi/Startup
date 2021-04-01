<?php
use App\General\Application;

$session = Application::$app->getSession();

if ($session->issetFlashMessages()) {
    echo $session->showFlashMessages();
}
?>
