<?php

namespace Sprungbrett\Component\Uuid;

use Sprungbrett\Component\Uuid\Model\Uuid;

interface IdentifiableInterface
{
    public function getUuid(): Uuid;

    public function getId(): string;
}
