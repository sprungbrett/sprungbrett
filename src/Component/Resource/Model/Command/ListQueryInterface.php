<?php

namespace Sprungbrett\Component\Resource\Model\Command;

interface ListQueryInterface
{
    public function __construct(string $entityClass, string $resourceKey, ?string $locale, string $route, array $query);

    public function getEntityClass(): string;

    public function getResourceKey(): string;

    public function getLocale(): ?string;

    public function getRoute(): string;

    public function getQuery(): array;
}
