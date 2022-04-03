<?php
declare(strict_types=1);

return [
    'battle_config' => require __DIR__ . '/battle_config.php',

    \Armeerenko\BattleSimulation\BattleSimulator::class => DI\autowire()
        ->constructorParameter('battleConfig', DI\get('battle_config')),
];
