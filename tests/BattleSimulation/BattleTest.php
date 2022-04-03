<?php
declare(strict_types=1);

namespace Test\BattleSimulation;

use Armeerenko\BattleSimulation\Battle;
use Armeerenko\BattleSimulation\Event\AttackWasFailed;
use Armeerenko\BattleSimulation\Event\AttackWasSuccessful;
use Armeerenko\BattleSimulation\Event\BattleWasEnded;
use Armeerenko\BattleSimulation\Event\BattleWasStarted;
use Armeerenko\BattleSimulation\Event\SoldiersWereKilledByExplosion;
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

        $battleEvents = $battle->pullEvents();
        $this->assertCount(1, $battleEvents);
        $this->assertInstanceOf(BattleWasStarted::class, $battleEvents[0]);
    }

    public function testBattleWasEnded(): void
    {
        $army1Soldiers = 50;
        $army2Soldiers = 45;

        $battle = Battle::start($army1Soldiers, $army2Soldiers);
        $battle->end();

        $this->assertTrue($battle->isEnded());
    }

    public function testBattleWasEndedWithTieGoesAsWinForArmy2(): void
    {
        $army1Soldiers = 50;
        $army2Soldiers = 50;

        $battle = Battle::start($army1Soldiers, $army2Soldiers);
        $battle->end();

        $this->assertTrue($battle->isEnded());
        $this->assertEquals(2, $battle->winnerArmyId());
    }

    public function testScenarioWhereBattleEndsWithBothSidesHavingSoldiersInBattle(): void
    {
        $army1Soldiers = 50;
        $army2Soldiers = 45;

        $battle = Battle::fromEvents([
            new BattleWasStarted($army1Soldiers, $army2Soldiers),
            new AttackWasSuccessful(1, 25),
            new AttackWasSuccessful(1, 15),
            new AttackWasFailed(2, 1),
            new AttackWasSuccessful(1, 3),
        ]);

        $battle->end();

        $this->assertEquals(7, $battle->army1Soldiers());
        $this->assertEquals(44, $battle->army2Soldiers());

        $this->assertTrue($battle->isEnded());
        $this->assertEquals(2, $battle->winnerArmyId());
    }

    public function testScenarioWhereBattleWinnerIsArmy1(): void
    {
        $army1Soldiers = 50;
        $army2Soldiers = 45;

        $battle = Battle::fromEvents([
            new BattleWasStarted($army1Soldiers, $army2Soldiers),
            new SoldiersWereKilledByExplosion(1, 40),
            new SoldiersWereKilledByExplosion(2, 10),
        ]);

        $this->assertEquals(10, $battle->army1Soldiers());
        $this->assertEquals(35, $battle->army2Soldiers());

        $this->assertFalse($battle->isEnded());
    }
}
