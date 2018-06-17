<?php

namespace Sprungbrett\Component\EventMiddleware;

use League\Tactician\Middleware;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventMiddleware implements Middleware
{
    /**
     * @var EventCollector
     */
    private $eventCollector;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventCollector $eventCollector, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventCollector = $eventCollector;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute($command, callable $next)
    {
        $result = $next($command);

        foreach ($this->eventCollector->release() as $name => $events) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($name, $event);
            }
        }

        return $result;
    }
}
