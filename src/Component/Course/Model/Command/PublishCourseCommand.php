<?php

namespace Sprungbrett\Component\Course\Model\Command;

use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class PublishCourseCommand
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
