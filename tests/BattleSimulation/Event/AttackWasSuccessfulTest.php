<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Event;

use Armeerenko\BattleSimulation\Event\AttackWasSuccessful;
use LogicException;
use Test\UnitTestCase;

final class AttackWasSuccessfulTest extends UnitTestCase
{
    /**
     * @dataProvider provideInvalidArgumentForArmyId
     */
    public function testThrowExceptionForInvalidArguments(int $armyId): void
    {
        $this->expectException(LogicException::class);

        new AttackWasSuccessful($armyId, 20);
    }

    /**
     * @dataProvider provideValidArgumentForArmyId
     */
    public function testCreateEventForValidArguments(int $armyId): void
    {
        $event = new AttackWasSuccessful($armyId, 20);

        $this->assertEquals($armyId, $event->defenderArmyId());
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
