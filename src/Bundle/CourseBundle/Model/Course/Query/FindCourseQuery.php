<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Query;

use Sprungbrett\Bundle\ContentBundle\Stages;

class FindCourseQuery
{
    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $stage;

    public function __construct(string $uuid, string $locale, string $stage = Stages::DRAFT)
    {
        $this->uuid = $uuid;
        $this->locale = $locale;
        $this->stage = $stage;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getStage(): string
    {
        return $this->stage;
    }
}
