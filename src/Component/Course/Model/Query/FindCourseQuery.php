<?php

namespace Sprungbrett\Component\Course\Model\Query;

use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class FindCourseQuery
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
