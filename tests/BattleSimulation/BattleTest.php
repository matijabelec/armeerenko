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

    public function testFailedAttackFromTooSmallArmyButNotEnded(): void
    {
        $army1Soldiers = 90;
        $army2Soldiers = 390;

        $battle = Battle::fromEvents([
            new BattleWasStarted($army1Soldiers, $army2Soldiers),
        ]);

        $battle->army1Attack();

        $events = $battle->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(AttackWasFailed::class, $events[0]);
        /** @var AttackWasFailed $attackWasFailed */
        $attackWasFailed = $events[0];
        $this->assertEquals(1, $attackWasFailed->attackerArmyId());
        $this->assertEquals(39, $attackWasFailed->hitsCount());
        $this->assertEquals(51, $battle->army1Soldiers());
        $this->assertEquals(390, $battle->army2Soldiers());
    }

    public function testFailedAttackFromTooSmallArmyAndBattleEnded(): void
    {
        $army1Soldiers = 90;
        $army2Soldiers = 990;

        $battle = Battle::fromEvents([
            new BattleWasStarted($army1Soldiers, $army2Soldiers),
        ]);

        $battle->army1Attack();

        $events = $battle->pullEvents();
        $this->assertCount(2, $events);
        $this->assertInstanceOf(AttackWasFailed::class, $events[0]);
        $this->assertInstanceOf(BattleWasEnded::class, $events[1]);
        /** @var AttackWasFailed $attackWasFailed */
        $attackWasFailed = $events[0];
        $this->assertEquals(1, $attackWasFailed->attackerArmyId());
        $this->assertEquals(90, $attackWasFailed->hitsCount());
        /** @var BattleWasEnded $battleWasEnded */
        $battleWasEnded = $events[1];
        $this->assertEquals(2, $battleWasEnded->winnerArmyId());
        $this->assertEquals(0, $battle->army1Soldiers());
        $this->assertEquals(990, $battle->army2Soldiers());
    }
}
