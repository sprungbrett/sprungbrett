<?php

declare(strict_types=1);

namespace Sprungbrett\Component\EventMiddleware\Tests;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Component\EventCollector\EventCollector;
use Sprungbrett\Component\EventMiddleware\EventMiddleware;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventMiddlewareTest extends TestCase
{
    public function testExecute()
    {
        $eventCollector = $this->prophesize(EventCollector::class);
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $middleware = new EventMiddleware($eventCollector->reveal(), $eventDispatcher->reveal());

        $eventCollector->release()->willReturn([]);
        $eventDispatcher->dispatch(Argument::cetera())->shouldNotBeCalled();

        $command = new \stdClass();

        $result = $middleware->handle(
            $command,
            function ($passed) use ($command) {
                $this->assertEquals($command, $passed);

                return 'Sprungbrett is awesome';
            }
        );

        $this->assertEquals('Sprungbrett is awesome', $result);
    }

    public function testExecuteWithEvents()
    {
        $eventCollector = $this->prophesize(EventCollector::class);
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $middleware = new EventMiddleware($eventCollector->reveal(), $eventDispatcher->reveal());

        $event1 = new Event();
        $event2 = new Event();

        $eventCollector->release()->willReturn(
            [
                'sprungbrett.entity.created' => [$event1],
                'sprungbrett.entity.modified' => [$event2],
            ]
        );
        $eventDispatcher->dispatch('sprungbrett.entity.created', $event1)->shouldBeCalled();
        $eventDispatcher->dispatch('sprungbrett.entity.modified', $event2)->shouldBeCalled();

        $command = new \stdClass();

        $middleware->handle(
            $command,
            function ($passed) {
            }
        );
    }
}
