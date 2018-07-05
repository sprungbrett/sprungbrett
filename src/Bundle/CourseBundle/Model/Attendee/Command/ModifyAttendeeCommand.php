<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Command;

use Sprungbrett\Component\Payload\Model\Command\PayloadTrait;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class ModifyAttendeeCommand
{
    use LocaleTrait;
    use PayloadTrait;

    /**
     * @var int
     */
    private $id;

    public function __construct(int $id, string $locale, array $payload)
    {
        $this->initializeLocale($locale);
        $this->initializePayload($payload);

        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->getStringValue('description');
    }
}
