<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

class RemoveCourseCommand
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}