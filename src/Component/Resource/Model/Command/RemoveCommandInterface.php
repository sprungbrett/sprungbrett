<?php

namespace Sprungbrett\Component\Resource\Model\Command;

interface RemoveCommandInterface
{
    public function __construct(string $id);

    public function getId(): string;
}
