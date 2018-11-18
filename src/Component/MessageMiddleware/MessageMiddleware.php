<?php

declare(strict_types=1);

namespace Sprungbrett\Component\MessageMiddleware;

use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class MessageMiddleware implements MiddlewareInterface
{
    /**
     * @var MessageCollector
     */
    private $collector;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(MessageCollector $collector, MessageBusInterface $messageBus)
    {
        $this->collector = $collector;
        $this->messageBus = $messageBus;
    }

    public function handle($message, callable $next)
    {
        $result = $next($message);

        foreach ($this->collector->release() as $collectedMessage) {
            $this->messageBus->dispatch($collectedMessage);
        }

        return $result;
    }
}
