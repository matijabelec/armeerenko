<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Event;

final class BattleWasStarted implements BattleEvent
{

    private int $army1Soldiers;
    private int $army2Soldiers;

    public function __construct(int $army1Soldiers, int $army2Soldiers)
    {
        $this->army1Soldiers = $army1Soldiers;
        $this->army2Soldiers = $army2Soldiers;
    }

    public function army1Soldiers(): int
    {
        return $this->army1Soldiers;
    }

    public function army2Soldiers(): int
    {
        return $this->army2Soldiers;
    }
}
