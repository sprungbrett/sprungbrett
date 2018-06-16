<?php

namespace Sprungbrett\Component\Uuid\Model;

interface UuidInterface
{
    public function getUuid(): Uuid;

    public function getId(): string;
}
