<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Event;

final class BattleWasEnded implements BattleEvent
{
    private int $winnerArmyId;

    public function __construct(int $winnerArmyId)
    {
        $this->winnerArmyId = $winnerArmyId;
    }

    public function winnerArmyId(): int
    {
        return $this->winnerArmyId;
    }
}
