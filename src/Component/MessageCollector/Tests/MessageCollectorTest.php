<?php

declare(strict_types=1);

namespace Sprungbrett\Component\MessageCollector\Tests;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class MessageCollectorTest extends TestCase
{
    public function testPush()
    {
        $message = $this->prophesize(\stdClass::class);

        $collector = new MessageCollector();
        $collector->push($message->reveal());

        $this->assertEquals([$message->reveal()], $collector->release());
    }

    public function testRelease()
    {
        $message = $this->prophesize(\stdClass::class);

        $collector = new MessageCollector();
        $collector->push($message->reveal());

        $this->assertEquals([$message->reveal()], $collector->release());
        $this->assertEquals([], $collector->release());
    }
}
