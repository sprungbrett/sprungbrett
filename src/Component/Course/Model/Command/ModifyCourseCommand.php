<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Uuid\Model\Command\IdTrait;

class ModifyCourseCommand extends MappingCourseCommand
{
    use IdTrait;

    public function __construct(string $id, string $locale, array $payload)
    {
        parent::__construct($locale, $payload);

        $this->initializeId($id);
    }
}
