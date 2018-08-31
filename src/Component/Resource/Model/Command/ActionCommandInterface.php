<?php

namespace Sprungbrett\Component\Resource\Model\Command;

use Sprungbrett\Component\Translation\Model\Localization;

interface ActionCommandInterface
{
    public function __construct(string $id, string $locale);

    public function getId(): string;

    public function getLocalization(): Localization;
}
