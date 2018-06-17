<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sprungbrett\Component\Translation\Model\Localization;

trait CourseTrait
{
    public function createCourse(
        string $title = 'Sprungbrett',
        string $description = 'Sprungbrett is awesome',
        string $locale = 'en'
    ): Course {
        $course = new Course();
        $course->setCurrentLocalization(new Localization($locale));
        $course->setTitle($title);
        $course->setDescription($description);

        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush();

        return $course;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
