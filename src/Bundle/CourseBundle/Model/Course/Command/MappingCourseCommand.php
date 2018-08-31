<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

use Sprungbrett\Component\Payload\Model\Command\PayloadTrait;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;
use Webmozart\Assert\Assert;

abstract class MappingCourseCommand
{
    use LocaleTrait;
    use PayloadTrait;

    public function __construct(string $locale, array $payload)
    {
        $this->initializeLocale($locale);
        $this->initializePayload($payload);
    }

    public function getName(): string
    {
        return $this->getStringValue('name');
    }

    public function getDescription(): string
    {
        return $this->getStringValue('description');
    }

    public function getTrainer(): ?array
    {
        $trainer = $this->getValueWithDefault('trainer', null);
        if (!$trainer) {
            return null;
        }

        Assert::isArray($trainer);
        Assert::keyExists($trainer, 'id');

        return $trainer;
    }
}
