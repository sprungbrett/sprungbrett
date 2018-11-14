<?php

namespace Sprungbrett\Component\Translatable\Model;

interface TranslatableInterface
{
    public function setCurrentLocale(string $currentLocale): void;

    public function getCurrentLocale(): ?string;
}
