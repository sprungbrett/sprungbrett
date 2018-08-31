<?php

namespace Sprungbrett\Component\Resource\Model\Command;

interface FindQueryInterface
{
    public function __construct(string $id, string $locale);

    public function getId(): string;
}
