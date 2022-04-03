<?php
declare(strict_types=1);

/** @var Router $router */

use Armeerenko\Feature\BattleAction;
use Armeerenko\Feature\HomeAction;
use Framework\Contract\Routing\Router;

$router->addRoute('GET', '/', HomeAction::class);
$router->addRoute('GET', '/battle', BattleAction::class);
