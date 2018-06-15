<?php

namespace Sprungbrett\Bundle\CourseBundle\Entity;

use Sprungbrett\Component\Course\Model\Course as ComponentCourse;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class Course extends ComponentCourse implements AuditableInterface
{
    use AuditableTrait;
}
