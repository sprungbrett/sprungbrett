<?php

namespace Sprungbrett\Bundle\CourseBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Course\Model\CourseTranslation as ComponentCourseTranslation;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @Serializer\ExclusionPolicy("ALL")
 */
class CourseTranslation extends ComponentCourseTranslation implements AuditableInterface
{
    use AuditableTrait;

    /**
     * @var Course
     */
    private $course;

    public function __construct(string $id, Localization $localization, ContentInterface $content, Course $course)
    {
        parent::__construct($id, $localization, $content);

        $this->course = $course;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }
}
