<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo;

interface SeoRepositoryInterface
{
    public function create(
        string $resourceKey,
        string $resourceId,
        string $stage,
        ?string $locale = null
    ): SeoInterface;

    public function findByResource(
        string $resourceKey,
        string $resourceId,
        string $stage,
        ?string $locale
    ): ?SeoInterface;

    /**
     * @return SeoInterface[]
     */
    public function findAllByResource(string $resourceKey, string $resourceId): array;

    public function remove(SeoInterface $seo): void;
}
