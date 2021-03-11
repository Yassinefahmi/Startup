<?php
use App\General\Application;

$session = Application::$app->getSession();

if ($session->issetFlashMessage('success')) {
    echo '<div class="alert alert-success">' . $session->getFlashMessage('success') . '</div>';
}
?>