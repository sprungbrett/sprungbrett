<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

interface ContentInterface
{
    public function __construct(string $resourceKey, string $resourceId, string $locale, string $stage);

    public function getResourceKey(): string;

    public function getResourceId(): string;

    public function getLocale(): string;

    public function getStage(): string;

    public function getType(): ?string;

    public function getData(): array;

    public function modifyData(?string $type, array $data): self;
}
