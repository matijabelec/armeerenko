<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

use Armeerenko\BattleSimulation\Event\BattleWasStarted;

final class Battle
{
    use BattleEventsTrait;

    private int $army1Soldiers;
    private int $army2Soldiers;

    public static function start(int $army1Soldiers, int $army2Soldiers): self
    {
        $battle = new self();

        $battle->recordThat(new BattleWasStarted($army1Soldiers, $army2Soldiers));

        return $battle;
    }

    protected function applyBattleWasStarted(BattleWasStarted $battleWasStarted): void
    {
        $this->army1Soldiers = $battleWasStarted->army1Soldiers();
        $this->army2Soldiers = $battleWasStarted->army2Soldiers();
    }

    private function __construct() {}
}
