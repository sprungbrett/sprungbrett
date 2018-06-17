<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Uuid\Model\Command\IdTrait;

class RemoveCourseCommand
{
    use IdTrait;

    public function __construct(string $id)
    {
        $this->initializeId($id);
    }
}
