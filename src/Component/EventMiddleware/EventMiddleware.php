<?php

declare(strict_types=1);

namespace Sprungbrett\Component\EventMiddleware;

use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class EventMiddleware implements MiddlewareInterface
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

    public function handle($message, callable $next)
    {
        $result = $next($message);

        foreach ($this->eventCollector->release() as $name => $events) {
            foreach ($events as $event) {
                $this->eventDispatcher->dispatch($name, $event);
            }
        }

        return $result;
    }
}
