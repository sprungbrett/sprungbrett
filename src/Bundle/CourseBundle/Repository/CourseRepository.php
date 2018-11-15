<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Sprungbrett\Bundle\CourseBundle\Model\Course;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Course::class);
    }

    public function create(string $uuid, string $stage, ?string $locale = null): CourseInterface
    {
        $course = new Course($uuid, $stage);
        if ($locale) {
            $course->setCurrentLocale($locale);
        }

        $this->entityManager->persist($course);

        return $course;
    }

    public function findById(string $uuid, string $stage, ?string $locale = null): ?CourseInterface
    {
        /** @var CourseInterface|null $course */
        $course = $this->repository->findOneBy(['uuid' => $uuid, 'stage' => $stage]);
        if ($course && $locale) {
            $course->setCurrentLocale($locale);
        }

        return $course;
    }

    public function findAllById(string $uuid): array
    {
        return $this->repository->findBy(['uuid' => $uuid]);
    }

    public function remove(CourseInterface $course): void
    {
        $this->entityManager->remove($course);
    }
}
