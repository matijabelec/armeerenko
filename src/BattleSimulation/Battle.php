<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

use Armeerenko\BattleSimulation\Event\AttackWasFailed;
use Armeerenko\BattleSimulation\Event\AttackWasSuccessful;
use Armeerenko\BattleSimulation\Event\BattleWasEnded;
use Armeerenko\BattleSimulation\Event\BattleWasStarted;
use Armeerenko\BattleSimulation\Event\SoldiersWereKilledByExplosion;
use Armeerenko\BattleSimulation\Exception\BattleIsAlreadyEnded;
use Armeerenko\BattleSimulation\Exception\TooManyHitsForArmySoldiers;
use Armeerenko\BattleSimulation\Explosion\ExplosionImpact;

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

    public function randomExplosion(ExplosionImpact $explosionImpact): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        $explosionResult = $explosionImpact->calculate($this->army1Soldiers, $this->army2Soldiers);

        if ($explosionResult->hits1() > $this->army1Soldiers || $explosionResult->hits2() > $this->army2Soldiers) {
            throw new TooManyHitsForArmySoldiers();
        }

        if ($explosionResult->hasHits1()) {
            $this->recordThat(new SoldiersWereKilledByExplosion(1, $explosionResult->hits1()));
        }

        if ($explosionResult->hasHits2()) {
            $this->recordThat(new SoldiersWereKilledByExplosion(2, $explosionResult->hits2()));
        }

        $this->checkEndingCondition();
    }

    public function army1Attack(): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        if ($this->army1Soldiers < (int) ($this->army2Soldiers * 0.25)) {
            $hits = min((int) ($this->army2Soldiers * 0.1), $this->army1Soldiers);
            $this->recordThat(new AttackWasFailed(1, $hits));
        } else {
            $hits = min((int)($this->army1Soldiers * 0.25), $this->army2Soldiers);
            $this->recordThat(new AttackWasSuccessful(2, $hits));
        }

        $this->checkEndingCondition();
    }

    public function army2Attack(): void
    {
        if ($this->isEnded()) {
            throw new BattleIsAlreadyEnded();
        }

        if ($this->army2Soldiers < (int) ($this->army1Soldiers * 0.25)) {
            $hits = min((int) ($this->army1Soldiers * 0.1), $this->army2Soldiers);
            $this->recordThat(new AttackWasFailed(2, $hits));
        } else {
            $hits = min((int)($this->army2Soldiers * 0.25), $this->army1Soldiers);
            $this->recordThat(new AttackWasSuccessful(1, $hits));
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
