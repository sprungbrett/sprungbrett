<?php

namespace Sprungbrett\Bundle\CourseBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Sprungbrett\Component\Course\Model\Course as ComponentCourse;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @Serializer\ExclusionPolicy("ALL")
 */
class Course extends ComponentCourse implements AuditableInterface
{
    use AuditableTrait;

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new CourseTranslation($localization, $this);
    }
}
