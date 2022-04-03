<?php
declare(strict_types=1);

namespace Armeerenko\BattleSimulation;

use Armeerenko\BattleSimulation\Event\BattleEvent;

trait BattleEventsTrait
{
    private array $events = [];

    protected function recordThat(BattleEvent $event): void
    {
        $this->apply($event);
        $this->events[] = $event;
    }

    protected function apply(BattleEvent $event): void
    {
        $method = sprintf('apply%s', (new \ReflectionClass($event))->getShortName());
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }

    /**
     * @return BattleEvent[]
     */
    public function pullEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }

    public static function fromEvents(array $events): self
    {
        $self = new static();

        foreach ($events as $event) {
            $self->apply($event);
        }

        return $self;
    }
}
