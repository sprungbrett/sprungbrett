<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Payload\Model\Command\PayloadTrait;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class CreateCourseCommand
{
    use LocaleTrait;
    use PayloadTrait;

    public function __construct(string $locale, array $payload)
    {
        $this->initializeLocale($locale);
        $this->initializePayload($payload);
    }

    public function getTitle(): string
    {
        return $this->getStringValue('title');
    }
}
