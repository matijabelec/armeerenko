<?php
declare(strict_types=1);

namespace Test\BattleSimulation\Event;

use Armeerenko\BattleSimulation\Event\BattleWasStarted;
use Test\UnitTestCase;

final class BattleWasStartedTest extends UnitTestCase
{
    public function testCreateEvent(): void
    {
        $event = new BattleWasStarted(10, 20);

        $this->assertEquals(10, $event->army1Soldiers());
        $this->assertEquals(20, $event->army2Soldiers());
    }
}
