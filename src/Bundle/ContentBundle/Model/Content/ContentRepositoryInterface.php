<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

interface ContentRepositoryInterface
{
    public function create(
        string $resourceKey,
        string $resourceId,
        string $locale,
        string $stage
    ): ContentInterface;

    public function findByResource(
        string $resourceKey,
        string $resourceId,
        string $locale,
        string $stage
    ): ?ContentInterface;

    /**
     * @return ContentInterface[]
     */
    public function findAllByResource(string $resourceKey, string $resourceId): array;

    public function remove(ContentInterface $content): void;
}
