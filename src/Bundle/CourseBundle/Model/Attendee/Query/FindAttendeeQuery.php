<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query;

use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class FindAttendeeQuery
{
    use LocaleTrait;

    /**
     * @var int
     */
    private $id;

    public function __construct(int $id, string $locale)
    {
        $this->initializeLocale($locale);

        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
