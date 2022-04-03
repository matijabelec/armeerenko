<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Event;

final class SoldiersWereKilledByExplosion implements BattleEvent
{
    private int $armyId;
    private int $count;

    public function __construct(int $armyId, int $count)
    {
        if (false === in_array($armyId, [1, 2])) {
            throw new \LogicException('Only armies 1 and 2 are in battle.');
        }

        $this->armyId = $armyId;
        $this->count = $count;
    }

    public function armyId(): int
    {
        return $this->armyId;
    }

    public function count(): int
    {
        return $this->count;
    }
}
