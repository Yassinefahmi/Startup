<?php
use App\General\Application;

$session = Application::$app->getSession();

if ($session->issetFlashMessages()) {
    foreach ($session->getFlashMessages() as $key => $description) {
        echo '<div class="alert alert-' . $key . '">' . $description['value'] . '</div>';
    }
}
?>