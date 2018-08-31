<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Command;

use Sprungbrett\Component\Resource\Model\Command\ActionCommandInterface;
use Sprungbrett\Component\Translation\Model\Command\LocaleTrait;

class PublishCourseCommand implements ActionCommandInterface
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
