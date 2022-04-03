<?php
declare(strict_types=1);

namespace Test\BattleSimulation;

use Armeerenko\BattleSimulation\BattleEventsTrait;
use Armeerenko\BattleSimulation\Event\BattleEvent;
use Test\UnitTestCase;

final class BattleEventTraitTest extends UnitTestCase
{
    public function testEventsDoesNotExistForNewlyCreatedAggregate(): void
    {
        $aggregate = new TestAggregate();

        $this->assertEmpty($aggregate->pullEvents());
    }

    public function testEventsAreNotRecordedForReconstruction(): void
    {
        $aggregate = TestAggregate::fromEvents([new TestBattleEvent()]);

        $this->assertEmpty($aggregate->pullEvents());
    }

    public function testEventsAreClearedAfterPulling(): void
    {
        $aggregate = new TestAggregate();
        $aggregate->addEvent();

        $events = $aggregate->pullEvents();
        $this->assertCount(1, $events);
        $this->assertEmpty($aggregate->pullEvents());
    }
}

final class TestBattleEvent implements BattleEvent
{
}

final class TestAggregate {
    use BattleEventsTrait;

    public function addEvent(): void
    {
        $this->recordThat(new TestBattleEvent());
    }
}
