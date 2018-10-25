<?php

declare(strict_types=1);

namespace Sprungbrett\Component\DependentMessageMiddleware\Tests;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\DependentMessageCollector\DependentMessageCollector;
use Sprungbrett\Component\DependentMessageMiddleware\DependentMessageMiddleware;

class DependentMessageMiddlewareTest extends TestCase
{
    public function testExecute()
    {
        $collector = $this->prophesize(DependentMessageCollector::class);

        $middleware = new DependentMessageMiddleware($collector->reveal());

        $collector->release()->willReturn([]);

        $message = new \stdClass();

        $result = $middleware->handle(
            $message,
            function ($passed) use ($message) {
                $this->assertEquals($message, $passed);

                return 'Sprungbrett is awesome';
            }
        );

        $this->assertEquals('Sprungbrett is awesome', $result);
    }

    public function testExecuteWithMessages()
    {
        $collector = $this->prophesize(DependentMessageCollector::class);

        $middleware = new DependentMessageMiddleware($collector->reveal());

        $message1 = new \stdClass();
        $message2 = new \stdClass();

        $collector->release()->willReturn(
            [
                $message1,
                $message2,
            ]
        );

        $message = new \stdClass();

        $middleware->handle(
            $message,
            function ($passed) use ($message, $message1, $message2) {
                $this->assertContains($passed, [$message, $message1, $message2]);
            }
        );
    }
}
