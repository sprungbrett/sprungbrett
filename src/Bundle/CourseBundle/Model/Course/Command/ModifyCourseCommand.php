<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

use Sprungbrett\Component\Resource\Model\Command\ModifyCommandInterface;

class ModifyCourseCommand extends MappingCourseCommand implements ModifyCommandInterface
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

    public function getStructureType(): string
    {
        return $this->getStringValue('template');
    }

    public function getContentData(): array
    {
        return $this->getArrayValueWithDefault('content');
    }
}
