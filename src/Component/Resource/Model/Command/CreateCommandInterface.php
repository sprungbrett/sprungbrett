<?php

namespace Sprungbrett\Component\Resource\Model\Command;

use Sprungbrett\Component\Translation\Model\Localization;

interface CreateCommandInterface
{
    public function __construct(string $locale, array $payload);

    public function getLocalization(): Localization;
}
