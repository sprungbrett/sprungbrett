<?php

declare(strict_types=1);

namespace Sprungbrett\Component\DependentMessageMiddleware;

use Sprungbrett\Component\DependentMessageCollector\DependentMessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class DependentMessageMiddleware implements MiddlewareInterface
{
    /**
     * @var DependentMessageCollector
     */
    private $collector;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(DependentMessageCollector $collector, MessageBusInterface $messageBus)
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
