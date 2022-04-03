<?php

use Framework\App;

require __DIR__ . '/../vendor/autoload.php';

$app = new App(null, __DIR__ . '/config.php');

$router = $app->router();
require __DIR__ . '/routes.php';

return $app;
