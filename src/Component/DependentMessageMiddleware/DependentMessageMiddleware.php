<?php

declare(strict_types=1);

namespace Sprungbrett\Component\DependentMessageMiddleware;

use Sprungbrett\Component\DependentMessageCollector\DependentMessageCollector;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

class DependentMessageMiddleware implements MiddlewareInterface
{
    /**
     * @var DependentMessageCollector
     */
    private $collector;

    public function __construct(DependentMessageCollector $collector)
    {
        $this->collector = $collector;
    }

    public function handle($message, callable $next)
    {
        $result = $next($message);

        foreach ($this->collector->release() as $collectedMessage) {
            $next($collectedMessage);
        }

        return $result;
    }
}
