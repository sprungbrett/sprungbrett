<?php

namespace Sprungbrett\Component\Uuid\Model;

trait UuidTrait
{
    /**
     * @var Uuid
     */
    protected $uuid;

    protected function initializeUuid(?Uuid $uuid = null)
    {
        $this->uuid = $uuid ?: new Uuid();
    }

    public function getId(): string
    {
        return $this->uuid->getId();
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }
}
