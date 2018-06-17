<?php

namespace Sprungbrett\Component\EventCollector\Tests;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\EventCollector\EventCollector;
use Symfony\Component\EventDispatcher\Event;

class EventCollectorTest extends TestCase
{
    public function testPush()
    {
        $event = $this->prophesize(Event::class);

        $collector = new EventCollector('app');
        $collector->push('entity', 'created', $event->reveal());

        $this->assertEquals(['app.entity.created' => [$event->reveal()]], $collector->release());
    }

    public function testRelease()
    {
        $event = $this->prophesize(Event::class);

        $collector = new EventCollector('app');
        $collector->push('entity', 'created', $event->reveal());

        $this->assertEquals(['app.entity.created' => [$event->reveal()]], $collector->release());
        $this->assertEquals([], $collector->release());
    }
}
