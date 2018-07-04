<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\MappingCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

trait CourseMappingTrait
{
    protected function map(CourseInterface $course, MappingCourseCommand $command): void
    {
        $course->setTitle($command->getTitle());
        $course->setDescription($command->getDescription());
        $course->setTrainerId($command->getTrainer()['id']);
    }
}
