<?php

declare(strict_types=1);

namespace Sprungbrett\Component\Translatable\Model;

interface TranslatableInterface
{
    public function setCurrentLocale(string $currentLocale): void;

    public function getCurrentLocale(): ?string;
}
