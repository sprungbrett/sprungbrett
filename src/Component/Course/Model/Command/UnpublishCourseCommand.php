<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;
use Sprungbrett\Component\Uuid\Model\Command\IdTrait;

class UnpublishCourseCommand
{
    use IdTrait;
    use LocaleTrait;

    public function __construct(string $id, string $locale)
    {
        $this->initializeId($id);
        $this->initializeLocale($locale);
    }
}
