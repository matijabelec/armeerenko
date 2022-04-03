<?php
declare(strict_types=1);

namespace Test\BattleSimulation;

use Armeerenko\BattleSimulation\Battle;
use Test\UnitTestCase;

final class BattleTest extends UnitTestCase
{
    public function testBattleWasStarted(): void
    {
        $army1Soldiers = 50;
        $army2Soldiers = 45;

        $battle = Battle::start($army1Soldiers, $army2Soldiers);

        $this->assertEquals(50, $battle->army1Soldiers());
        $this->assertEquals(45, $battle->army2Soldiers());
    }
}
