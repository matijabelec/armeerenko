<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation\Event;

use LogicException;

final class AttackWasFailed implements BattleEvent
{
    private int $attackerArmyId;
    private int $hitsCount;

    public function __construct(int $attackerArmyId, int $hitsCount)
    {
        if (false === in_array($attackerArmyId, [1, 2])) {
            throw new LogicException('Only armies 1 and 2 are in battle.');
        }

        $this->attackerArmyId = $attackerArmyId;
        $this->hitsCount = $hitsCount;
    }

    public function attackerArmyId(): int
    {
        return $this->attackerArmyId;
    }

    public function hitsCount(): int
    {
        return $this->hitsCount;
    }

    public function summary(): string
    {
        return sprintf(
            'Attack from army %d was failed. Own soldiers killed in attack: %d.',
            $this->attackerArmyId,
            $this->hitsCount
        );
    }
}
