<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Seo;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoRepositoryInterface;

class SeoRepository implements SeoRepositoryInterface
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
        $this->repository = $entityManager->getRepository(Seo::class);
    }

    public function create(
        string $resourceKey,
        string $resourceId,
        string $stage,
        ?string $locale = null
    ): SeoInterface {
        $seo = new Seo($resourceKey, $resourceId, $stage);
        $this->entityManager->persist($seo);
        if ($locale) {
            $seo->setCurrentLocale($locale);
        }

        return $seo;
    }

    public function findByResource(
        string $resourceKey,
        string $resourceId,
        string $stage,
        ?string $locale = null
    ): ?SeoInterface {
        /** @var SeoInterface|null $seo */
        $seo = $this->repository->findOneBy(
            ['resourceKey' => $resourceKey, 'resourceId' => $resourceId, 'stage' => $stage]
        );

        if ($seo && $locale) {
            $seo->setCurrentLocale($locale);
        }

        return $seo;
    }

    public function findAllByResource(string $resourceKey, string $resourceId): array
    {
        return $this->repository->findBy(['resourceKey' => $resourceKey, 'resourceId' => $resourceId]);
    }

    public function remove(SeoInterface $seo): void
    {
        $this->entityManager->remove($seo);
    }
}
