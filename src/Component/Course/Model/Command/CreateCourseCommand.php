<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Payload\PayloadTrait;

class CreateCourseCommand
{
    use PayloadTrait;

    public function __construct(array $payload)
    {
        $this->initializePayload($payload);
    }

    public function getTitle(): string
    {
        return $this->getStringValue('title');
    }
}
