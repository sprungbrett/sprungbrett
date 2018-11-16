<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content;

use Sprungbrett\Component\Translatable\Model\TranslationInterface;

interface ContentTranslationInterface extends TranslationInterface
{
    public function __construct(ContentInterface $content, string $locale);

    public function getContent(): ContentInterface;

    public function getType(): ?string;

    public function getData(): array;

    public function modifyData(?string $type, array $data): self;
}
