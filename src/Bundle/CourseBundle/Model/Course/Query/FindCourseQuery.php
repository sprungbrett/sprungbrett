<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Query;

use Sprungbrett\Component\Resource\Model\Command\FindQueryInterface;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class FindCourseQuery implements FindQueryInterface
{
    use LocaleTrait;

    /**
     * @var string
     */
    private $id;

    public function __construct(string $id, string $locale)
    {
        $this->initializeLocale($locale);

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
