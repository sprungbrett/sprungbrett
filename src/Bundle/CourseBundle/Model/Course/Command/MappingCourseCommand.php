<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

use Sprungbrett\Component\Payload\Model\Command\PayloadTrait;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

abstract class MappingCourseCommand
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

    public function getDescription(): string
    {
        return $this->getStringValue('description');
    }
}
