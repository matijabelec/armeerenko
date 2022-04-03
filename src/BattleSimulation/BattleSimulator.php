<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

final class BattleSimulator
{
    public function simulate(int $army1Soldiers, int $army2Soldiers): Battle
    {
        $battle = Battle::start($army1Soldiers, $army2Soldiers);

        foreach (range(1, 20) as $actions) {
            $case = random_int(0, 1100);
            if ($case < 500) {
                $battle->army1Attack();
            } else if ($case < 1000) {
                $battle->army2Attack();
            } else {
                $battle->randomExplosion();
            }

            if ($battle->isEnded()) {
                return $battle;
            }
        }

        $battle->end();

        return $battle;
    }
}
