<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message;

use Ramsey\Uuid\Uuid;
use Sprungbrett\Component\Payload\Model\Message\PayloadTrait;

class CreateScheduleMessage
{
    use PayloadTrait {
        __construct as protected initializePayload;
    }

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale, array $payload)
    {
        $this->uuid = Uuid::uuid4()->toString();
        $this->locale = $locale;

        $this->initializePayload($payload);
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getCourse(): string
    {
        return $this->getStringValue('course');
    }

    public function getMaximumParticipants(): int
    {
        return $this->getIntValue('maximumParticipants');
    }

    public function getMinimumParticipants(): int
    {
        return $this->getIntValue('minimumParticipants');
    }

    public function getPrice(): float
    {
        return $this->getFloatValue('price');
    }

    public function getName(): string
    {
        return $this->getStringValue('name');
    }

    public function getDescription(): string
    {
        return $this->getStringValue('description');
    }
}
