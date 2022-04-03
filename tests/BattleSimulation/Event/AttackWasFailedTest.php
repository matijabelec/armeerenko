<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Event;

use Armeerenko\BattleSimulation\Event\AttackWasFailed;
use LogicException;
use Test\UnitTestCase;

final class AttackWasFailedTest extends UnitTestCase
{
    /**
     * @dataProvider provideInvalidArgumentForArmyId
     */
    public function testThrowExceptionForInvalidArguments(int $armyId): void
    {
        $this->expectException(LogicException::class);

        new AttackWasFailed($armyId, 20);
    }

    /**
     * @dataProvider provideValidArgumentForArmyId
     */
    public function testCreateEventForValidArguments(int $armyId): void
    {
        $event = new AttackWasFailed($armyId, 20);

        $this->assertEquals($armyId, $event->attackerArmyId());
        $this->assertEquals(20, $event->hitsCount());
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
