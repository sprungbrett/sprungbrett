<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

use Sprungbrett\Component\Translatable\Model\TranslatableInterface;

interface ContentInterface extends TranslatableInterface
{
    public function __construct(string $resourceKey, string $resourceId);

    public function getResourceKey(): string;

    public function getResourceId(): string;

    public function getStage(): string;

    public function getType(?string $locale = null): ?string;

    public function getData(?string $locale = null): array;

    public function modifyData(?string $type, array $data): self;
}
