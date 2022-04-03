<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Event;

use LogicException;

final class BattleWasEnded implements BattleEvent
{
    private int $winnerArmyId;

    public function __construct(int $winnerArmyId)
    {
        if (false === in_array($winnerArmyId, [1, 2])) {
            throw new LogicException('Only armies 1 and 2 are in battle.');
        }

        $this->winnerArmyId = $winnerArmyId;
    }

    public function winnerArmyId(): int
    {
        return $this->winnerArmyId;
    }

    public function summary(): string
    {
        return sprintf(
            'Battle was ended. Army %d won.',
            $this->winnerArmyId
        );
    }
}
