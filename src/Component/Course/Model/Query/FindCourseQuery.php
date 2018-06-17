<?php

namespace Sprungbrett\Component\Course\Model\Query;

use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;
use Sprungbrett\Component\Uuid\Model\Command\IdTrait;

class FindCourseQuery
{
    use IdTrait;
    use LocaleTrait;

    public function __construct(string $id, string $locale)
    {
        $this->initializeId($id);
        $this->initializeLocale($locale);
    }
}
