<?php

namespace Sprungbrett\Component\Course\Model\Query;

use Sprungbrett\Component\Uuid\Model\Uuid;

class FindCourseQuery
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getUuid(): Uuid
    {
        return new Uuid($this->id);
    }
}
