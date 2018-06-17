<?php

namespace Sprungbrett\Component\EventCollector;

use Symfony\Component\EventDispatcher\Event;

class EventCollector
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var Event[][]
     */
    private $events = [];

    public function __construct(string $prefix = 'sprungbrett')
    {
        $this->prefix = $prefix;
    }

    public function push(string $componentName, string $eventName, Event $event): void
    {
        $eventName = sprintf('%s.%s.%s', $this->prefix, $componentName, $eventName);
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
