<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Event;

use Armeerenko\BattleSimulation\Event\AttackWasFailed;
use Armeerenko\BattleSimulation\Event\BattleWasEnded;
use LogicException;
use Test\UnitTestCase;

final class BattleWasEndedTest extends UnitTestCase
{
    /**
     * @dataProvider provideInvalidArgumentForArmyId
     */
    public function testThrowExceptionForInvalidArguments(int $armyId): void
    {
        $this->expectException(LogicException::class);

        new BattleWasEnded($armyId);
    }

    /**
     * @dataProvider provideValidArgumentForArmyId
     */
    public function testCreateEventForValidArguments(int $armyId): void
    {
        $event = new BattleWasEnded($armyId);

        $this->assertEquals($armyId, $event->winnerArmyId());
    }

    public function provideInvalidArgumentForArmyId(): array
    {
        return [
            [0, ],
            [3, ],
            [10, ],
        ];
    }

    public function provideValidArgumentForArmyId(): array
    {
        return [
            [1, ],
            [2, ],
        ];
    }
}
