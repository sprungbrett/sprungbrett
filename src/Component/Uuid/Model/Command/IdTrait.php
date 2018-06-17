<?php

namespace Sprungbrett\Component\Uuid\Model\Command;

use Sprungbrett\Component\Uuid\Model\Uuid;

trait IdTrait
{
    /**
     * @var string
     */
    protected $id;

    public function initializeId(string $id): void
    {
        $this->id = $id;
    }

    public function getUuid(): Uuid
    {
        return new Uuid($this->id);
    }
}
