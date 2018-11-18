<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var ObjectRepository
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
        string $stage,
        ?string $locale = null
    ): ContentInterface {
        $content = new Content($resourceKey, $resourceId, $stage);
        $this->entityManager->persist($content);
        if ($locale) {
            $content->setCurrentLocale($locale);
        }

        return $content;
    }

    public function findByResource(
        string $resourceKey,
        string $resourceId,
        string $stage,
        ?string $locale = null
    ): ?ContentInterface {
        /** @var ContentInterface|null $content */
        $content = $this->repository->findOneBy(
            ['resourceKey' => $resourceKey, 'resourceId' => $resourceId, 'stage' => $stage]
        );

        if ($content && $locale) {
            $content->setCurrentLocale($locale);
        }

        return $content;
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
