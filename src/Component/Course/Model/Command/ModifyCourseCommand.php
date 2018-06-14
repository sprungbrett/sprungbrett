<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Payload\PayloadTrait;
use Sprungbrett\Component\Uuid\Model\Uuid;

class ModifyCourseCommand
{
    use PayloadTrait;

    /**
     * @var string
     */
    private $id;

    public function __construct(string $id, array $payload)
    {
        $this->initializePayload($payload);
        $this->id = $id;
    }

    public function getUuid(): Uuid
    {
        return new Uuid($this->id);
    }

    public function getTitle(): string
    {
        return $this->getStringValue('title');
    }
}
