<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer\Event;

use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Symfony\Component\EventDispatcher\Event;

abstract class TrainerEvent extends Event
{
    const COMPONENT_NAME = 'trainer';
    const NAME = self::NAME;

    /**
     * @var TrainerInterface
     */
    private $trainer;

    public function __construct(TrainerInterface $trainer)
    {
        $this->trainer = $trainer;
    }

    public function getTrainer(): TrainerInterface
    {
        return $this->trainer;
    }
}
