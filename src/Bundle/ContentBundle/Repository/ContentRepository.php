<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Content;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;

class ContentRepository implements ContentRepositoryInterface
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
        $this->repository = $entityManager->getRepository(Content::class);
    }

    public function create(
        string $resourceKey,
        string $resourceId,
        string $locale,
        string $stage
    ): ContentInterface {
        $content = new Content($resourceKey, $resourceId, $locale, $stage);
        $this->entityManager->persist($content);

        return $content;
    }

    public function findByResource(
        string $resourceKey,
        string $resourceId,
        string $locale,
        string $stage
    ): ?ContentInterface {
        return $this->repository->findOneBy(
            ['resourceKey' => $resourceKey, 'resourceId' => $resourceId, 'locale' => $locale, 'stage' => $stage]
        );
    }

    public function findAllByResource(string $resourceKey, string $resourceId): array
    {
        return $this->repository->findBy(['resourceKey' => $resourceKey, 'resourceId' => $resourceId]);
    }

    public function remove(ContentInterface $content): void
    {
        $this->entityManager->remove($content);
    }
}
