<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Event;

use LogicException;

final class AttackWasSuccessful implements BattleEvent
{
    private int $defenderArmyId;
    private int $hitsCount;

    public function __construct(int $defenderArmyId, int $hitsCount)
    {
        if (false === in_array($defenderArmyId, [1, 2])) {
            throw new LogicException('Only armies 1 and 2 are in battle.');
        }

        $this->defenderArmyId = $defenderArmyId;
        $this->hitsCount = $hitsCount;
    }

    public function defenderArmyId(): int
    {
        return $this->defenderArmyId;
    }

    public function hitsCount(): int
    {
        return $this->hitsCount;
    }

    public function summary(): string
    {
        return sprintf(
            'Attack on army %d was successful. Soldiers killed in attack: %d.',
            $this->defenderArmyId,
            $this->hitsCount
        );
    }
}
