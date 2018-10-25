<?php

declare(strict_types=1);

namespace Sprungbrett\Component\EventCollector;

use Symfony\Component\EventDispatcher\Event;

class EventCollector
{
    /**
     * @var Event[][]
     */
    private $events = [];

    public function push(string $eventName, Event $event): void
    {
        if (!array_key_exists($eventName, $this->events)) {
            $this->events[$eventName] = [];
        }

        $this->events[$eventName][] = $event;
    }

    /**
     * @return Event[][]
     */
    public function release(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
