<?php

namespace Sprungbrett\Component\Course\Model\Command;

class ModifyCourseCommand extends MappingCourseCommand
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id, string $locale, array $payload)
    {
        parent::__construct($locale, $payload);

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
