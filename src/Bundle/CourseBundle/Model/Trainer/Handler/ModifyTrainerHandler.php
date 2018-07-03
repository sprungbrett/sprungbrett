<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Command\ModifyTrainerCommand;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Event\TrainerModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
use Sprungbrett\Component\EventCollector\EventCollector;

class ModifyTrainerHandler
{
    /**
     * @var TrainerRepositoryInterface
     */
    private $trainerRepository;

    /**
     * @var EventCollector
     */
    private $eventCollector;

    public function __construct(TrainerRepositoryInterface $trainerRepository, EventCollector $eventCollector)
    {
        $this->trainerRepository = $trainerRepository;
        $this->eventCollector = $eventCollector;
    }

    public function handle(ModifyTrainerCommand $command): TrainerInterface
    {
        $trainer = $this->trainerRepository->findOrCreateTrainerById($command->getId(), $command->getLocalization());
        $trainer->setDescription($command->getDescription());

        $event = new TrainerModifiedEvent($trainer);
        $this->eventCollector->push($event::COMPONENT_NAME, $event::NAME, $event);

        return $trainer;
    }
}
