<?php

namespace Sprungbrett\Component\Course\Model;

use Sprungbrett\Component\Uuid\Model\Uuid;
use Sprungbrett\Component\Uuid\UuidTrait;

class Course
{
    use UuidTrait;

    /**
     * @var string
     */
    private $title;

    public function __construct(?Uuid $uuid = null)
    {
        $this->initializeUuid($uuid);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
