<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseView;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;

class CourseViewRepository implements CourseViewRepositoryInterface
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
        $this->repository = $entityManager->getRepository(CourseView::class);
    }

    public function create(string $uuid, string $locale): CourseViewInterface
    {
        $courseView = new CourseView($uuid, $locale);
        $this->entityManager->persist($courseView);

        return $courseView;
    }

    public function findById(string $uuid, string $locale): ?CourseViewInterface
    {
        /** @var CourseViewInterface|null $courseView */
        $courseView = $this->repository->find(['uuid' => $uuid, 'locale' => $locale]);

        return $courseView;
    }

    public function findAllById(string $uuid): array
    {
        return $this->repository->findBy(['uuid' => $uuid]);
    }

    public function list(int $page, int $pageSize): array
    {
        return $this->repository->findBy([], [], $pageSize, ($page - 1) * $pageSize);
    }

    public function remove(CourseViewInterface $courseView): void
    {
        $this->entityManager->remove($courseView);
    }
}
