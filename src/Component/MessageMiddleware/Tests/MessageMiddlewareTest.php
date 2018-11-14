<?php

declare(strict_types=1);

namespace Sprungbrett\Component\MessageMiddleware\Tests;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Sprungbrett\Component\MessageMiddleware\MessageMiddleware;
use Symfony\Component\Messenger\MessageBusInterface;

class MessageMiddlewareTest extends TestCase
{
    public function testExecute()
    {
        $collector = $this->prophesize(MessageCollector::class);
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $middleware = new MessageMiddleware($collector->reveal(), $messageBus->reveal());

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
        $collector = $this->prophesize(MessageCollector::class);
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $middleware = new MessageMiddleware($collector->reveal(), $messageBus->reveal());

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
            function ($passed) use ($message) {
                $this->assertEquals($message, $passed);
            }
        );

        $messageBus->dispatch($message1)->shouldBeCalled();
        $messageBus->dispatch($message2)->shouldBeCalled();
    }
}
