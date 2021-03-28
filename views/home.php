<h1>Je bent ingelogd</h1>
<?php
var_dump(\App\General\Application::$app->getAuthenticatedUser()->getAttributeValue('username'));
