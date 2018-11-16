<?php

namespace Sprungbrett\Bundle\PortalBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
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
     * @var EntityRepository
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
        return $this->repository->find(['uuid' => $uuid, 'locale' => $locale]);
    }

    public function list(int $page, int $pageSize): array
    {
        return $this->repository->findBy([], [], $pageSize, ($page - 1) * $pageSize);
    }
}
