<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Schedule;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Schedule::class);
    }

    public function create(
        string $uuid,
        CourseInterface $course,
        string $stage,
        ?string $locale = null
    ): ScheduleInterface {
        $schedule = new Schedule($uuid, $course, $stage);
        $this->entityManager->persist($schedule);

        if ($locale) {
            $schedule->setCurrentLocale($locale);
        }

        return $schedule;
    }

    public function findById(string $uuid, string $stage, ?string $locale = null): ?ScheduleInterface
    {
        /** @var ScheduleInterface|null $schedule */
        $schedule = $this->repository->findOneBy(['uuid' => $uuid, 'stage' => $stage]);

        if ($schedule && $locale) {
            $schedule->setCurrentLocale($locale);
        }

        return $schedule;
    }

    public function findAllById(string $uuid): array
    {
        return $this->repository->findBy(['uuid' => $uuid]);
    }

    public function remove(ScheduleInterface $schedule): void
    {
        $this->entityManager->remove($schedule);
    }
}
