<?php

namespace Sprungbrett\Component\Course\Model\Handler;

use Sprungbrett\Component\Course\Model\Command\MappingCourseCommand;
use Sprungbrett\Component\Course\Model\CourseInterface;

abstract class MappingCourseHandler
{
    protected function map(CourseInterface $course, MappingCourseCommand $command): void
    {
        $course->setTitle($command->getTitle());
        $course->setDescription($command->getDescription());
    }
}
