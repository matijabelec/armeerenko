<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Event;

use Armeerenko\BattleSimulation\Event\SoldiersWereKilledByExplosion;
use LogicException;
use Test\UnitTestCase;

final class SoldiersWereKilledByExplosionTest extends UnitTestCase
{
    /**
     * @dataProvider provideInvalidArgumentForArmyId
     */
    public function testThrowExceptionForInvalidArguments(int $armyId): void
    {
        $this->expectException(LogicException::class);

        new SoldiersWereKilledByExplosion($armyId, 20);
    }

    /**
     * @dataProvider provideValidArgumentForArmyId
     */
    public function testCreateEventForValidArguments(int $armyId): void
    {
        $event = new SoldiersWereKilledByExplosion($armyId, 20);

        $this->assertEquals($armyId, $event->armyId());
        $this->assertEquals(20, $event->count());
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
