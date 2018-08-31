<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

use Sprungbrett\Component\Resource\Model\Command\RemoveCommandInterface;

class RemoveCourseCommand implements RemoveCommandInterface
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
