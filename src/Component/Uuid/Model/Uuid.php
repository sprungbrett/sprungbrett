<?php

namespace Sprungbrett\Component\Uuid\Model;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    /**
     * @var string
     */
    private $id;

    public function __construct(?string $id = null)
    {
        $this->id = $id ?: RamseyUuid::uuid4()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
