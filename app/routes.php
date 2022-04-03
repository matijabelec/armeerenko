<?php
declare(strict_types=1);

/** @var Router $router */

use Armeerenko\Feature\HomeAction;
use Framework\Contract\Routing\Router;

$router->addRoute('GET', '/', HomeAction::class);
