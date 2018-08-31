<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\MappingCourseCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;

trait CourseMappingTrait
{
    /**
     * @var TrainerRepositoryInterface
     */
    private $trainerRepository;

    public function initializeCourseMapping(TrainerRepositoryInterface $trainerRepository): void
    {
        $this->trainerRepository = $trainerRepository;
    }

    protected function map(CourseInterface $course, MappingCourseCommand $command): void
    {
        $course->setName($command->getName());
        $course->setDescription($command->getDescription());

        if ($command->getTrainer()) {
            $trainer = $this->trainerRepository->findOrCreateTrainerById(
                $command->getTrainer()['id'],
                $course->getLocalization()
            );
            $course->setTrainerId($trainer->getId());
        }
    }
}
