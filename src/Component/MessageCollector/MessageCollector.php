<?php

declare(strict_types=1);

namespace Sprungbrett\Component\MessageCollector;

class MessageCollector
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
