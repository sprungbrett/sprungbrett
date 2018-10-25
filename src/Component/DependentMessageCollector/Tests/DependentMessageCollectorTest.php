<?php

declare(strict_types=1);

namespace Sprungbrett\Component\DependentMessageCollector\Tests;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\DependentMessageCollector\DependentMessageCollector;

class DependentMessageCollectorTest extends TestCase
{
    public function testPush()
    {
        $message = $this->prophesize(\stdClass::class);

        $collector = new DependentMessageCollector();
        $collector->push($message->reveal());

        $this->assertEquals([$message->reveal()], $collector->release());
    }

    public function testRelease()
    {
        $message = $this->prophesize(\stdClass::class);

        $collector = new DependentMessageCollector();
        $collector->push($message->reveal());

        $this->assertEquals([$message->reveal()], $collector->release());
        $this->assertEquals([], $collector->release());
    }
}
