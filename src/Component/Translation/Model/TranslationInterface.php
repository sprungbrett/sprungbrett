<?php

namespace Sprungbrett\Component\Translation\Model;

interface TranslationInterface
{
    public function getLocalization(): Localization;

    public function getLocale(): string;
}
