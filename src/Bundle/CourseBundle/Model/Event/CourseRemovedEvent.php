<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Event;

class CourseRemovedEvent
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
