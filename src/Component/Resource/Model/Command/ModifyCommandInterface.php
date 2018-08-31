<?php

namespace Sprungbrett\Component\Resource\Model\Command;

use Sprungbrett\Component\Translation\Model\Localization;

interface ModifyCommandInterface
{
    public function __construct(string $id, string $locale, array $payload);

    public function getId(): string;

    public function getLocalization(): Localization;
}
