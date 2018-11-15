<?php

declare(strict_types=1);

namespace Sprungbrett\Component\Translatable\Model;

interface TranslationInterface
{
    public function getLocale(): string;
}
