<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

final class BattleSimulator
{
    private array $battleConfig;

    public function __construct(array $battleConfig)
    {
        $this->battleConfig = $battleConfig;
    }

    public function simulate(int $army1Soldiers, int $army2Soldiers): Battle
    {
        $battle = Battle::start($army1Soldiers, $army2Soldiers);

        $army1Attack = $this->battleConfig['army1']['attack'];
        $army2Attack = $this->battleConfig['army2']['attack'];
        $explosionsRate = $this->battleConfig['explosions']['rate'];

        foreach (range(1, 20) as $day) {
            if ($this->makeAttempt($explosionsRate)) {
                $battle->randomExplosion();

                if ($battle->isEnded()) {
                    return $battle;
                }
            }

            if ($this->makeAttempt($army1Attack)) {
                $battle->army1Attack();

                if ($battle->isEnded()) {
                    return $battle;
                }
            }

            if ($this->makeAttempt($army2Attack)) {
                $battle->army2Attack();

                if ($battle->isEnded()) {
                    return $battle;
                }
            }
        }

        $battle->end();

        return $battle;
    }

    private function makeAttempt(float $maxValue): bool
    {
        $attempt = random_int(0, 100) / 100.0;

        return $attempt < $maxValue;
    }
}
