<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

use Armeerenko\BattleSimulation\Event\AttackWasFailed;
use Armeerenko\BattleSimulation\Event\AttackWasSuccessful;
use Armeerenko\BattleSimulation\Event\BattleWasEnded;
use Armeerenko\BattleSimulation\Event\BattleWasStarted;
use Armeerenko\BattleSimulation\Event\SoldiersWereKilledByExplosion;
use Armeerenko\BattleSimulation\Exception\BattleIsAlreadyEnded;

final class Battle
{
    use BattleEventsTrait;

    private int $army1Soldiers;
    private int $army2Soldiers;
    private bool $isEnded = false;
    private int $winnerArmyId;

    public static function start(int $army1Soldiers, int $army2Soldiers): self
    {
        $battle = new self();

        $battle->recordThat(new BattleWasStarted($army1Soldiers, $army2Soldiers));

        return $battle;
    }

    public function end(): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        if ($this->army1Soldiers > $this->army2Soldiers) {
            $winnerArmyId = 1;
        } else {
            $winnerArmyId = 2;
        }

        $this->recordThat(new BattleWasEnded($winnerArmyId));
    }

    public function randomExplosion(): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        $maxHits = min($this->army1Soldiers, $this->army2Soldiers);

        $hits1 = random_int(0, $maxHits);
        if ($hits1 > 0) {
            $this->recordThat(new SoldiersWereKilledByExplosion(1, $hits1));
        }

        $hits2 = random_int(0, $maxHits);
        if ($hits2 > 0) {
            $this->recordThat(new SoldiersWereKilledByExplosion(2, $hits2));
        }

        $this->checkEndingCondition();
    }

    public function army1Attack(): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        $attackingArmy = 1;
        $defendingArmy = 2;

        $hits = random_int(0, $this->army1Soldiers);
        if ($hits < (int) ($this->army1Soldiers / 2.0)) {
            $this->recordThat(new AttackWasFailed($attackingArmy, min($this->army1Soldiers, (int) ($hits / 2.0))));
        } else {
            $this->recordThat(new AttackWasSuccessful($defendingArmy, min($this->army2Soldiers, $hits)));
        }

        $this->checkEndingCondition();
    }

    public function army2Attack(): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        $attackingArmy = 2;
        $defendingArmy = 1;

        $hits = random_int(0, $this->army2Soldiers);
        if ($hits < (int) ($this->army2Soldiers / 2.0)) {
            $this->recordThat(new AttackWasFailed($attackingArmy, min($this->army2Soldiers, (int) ($hits / 2.0))));
        } else {
            $this->recordThat(new AttackWasSuccessful($defendingArmy, min($this->army1Soldiers, $hits)));
        }

        $this->checkEndingCondition();
    }

    public function army1Soldiers(): int
    {
        return $this->army1Soldiers;
    }

    public function army2Soldiers(): int
    {
        return $this->army2Soldiers;
    }

    public function winnerArmyId(): int
    {
        if (false === $this->isEnded()) {
            throw new \LogicException('Battle is not finished yet.');
        }

        return $this->winnerArmyId;
    }

    public function isEnded(): bool
    {
        return $this->isEnded;
    }

    protected function applyBattleWasStarted(BattleWasStarted $battleWasStarted): void
    {
        $this->army1Soldiers = $battleWasStarted->army1Soldiers();
        $this->army2Soldiers = $battleWasStarted->army2Soldiers();
    }

    protected function applyBattleWasEnded(BattleWasEnded $battleWasEnded): void
    {
        $this->winnerArmyId = $battleWasEnded->winnerArmyId();
        $this->isEnded = true;
    }

    protected function applySoldiersWereKilledByExplosion(
        SoldiersWereKilledByExplosion $soldiersWereKilledByExplosion
    ): void {
        if (1 === $soldiersWereKilledByExplosion->armyId()) {
            $this->army1Soldiers -= $soldiersWereKilledByExplosion->count();
        } else {
            $this->army2Soldiers -= $soldiersWereKilledByExplosion->count();
        }
    }

    protected function applyAttackWasSuccessful(
        AttackWasSuccessful $attackWasSuccessful
    ): void {
        if (1 === $attackWasSuccessful->defenderArmyId()) {
            $this->army1Soldiers -= $attackWasSuccessful->hitsCount();
        } else {
            $this->army2Soldiers -= $attackWasSuccessful->hitsCount();
        }
    }

    protected function applyAttackWasFailed(
        AttackWasFailed $attackWasFailed
    ): void {
        if (1 === $attackWasFailed->attackerArmyId()) {
            $this->army1Soldiers -= $attackWasFailed->hitsCount();
        } else {
            $this->army2Soldiers -= $attackWasFailed->hitsCount();
        }
    }

    private function checkEndingCondition(): void
    {
        if ($this->army1Soldiers < 1 || $this->army2Soldiers < 1) {
            $this->end();
        }
    }

    private function __construct() {}
}
