<?php

namespace Sprungbrett\Component\Uuid\Model;

interface IdentifiableInterface
{
    public function getUuid(): Uuid;

    public function getId(): string;
}
