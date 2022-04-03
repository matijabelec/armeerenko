<?php
declare(strict_types=1);

/** @var App $app */

use Framework\App;

$app = require __DIR__ . '/../app/bootstrap.php';

$app->run();
