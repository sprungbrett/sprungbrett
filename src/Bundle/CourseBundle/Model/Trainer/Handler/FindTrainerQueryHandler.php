<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Query\FindTrainerQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;

class FindTrainerQueryHandler
{
    /**
     * @var TrainerRepositoryInterface
     */
    private $trainerRepository;

    public function __construct(TrainerRepositoryInterface $trainerRepository)
    {
        $this->trainerRepository = $trainerRepository;
    }

    public function handle(FindTrainerQuery $query): TrainerInterface
    {
        return $this->trainerRepository->findOrCreateTrainerById($query->getId(), $query->getLocalization());
    }
}
