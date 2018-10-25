<?php

declare(strict_types=1);

namespace Sprungbrett\Component\DependentMessageCollector;

class DependentMessageCollector
{
    /**
     * @var object[]
     */
    private $message = [];

    public function push($message): void
    {
        $this->message[] = $message;
    }

    /**
     * @return object[]
     */
    public function release(): array
    {
        $messages = $this->message;
        $this->message = [];

        return $messages;
    }
}
