<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sprungbrett\Component\Translation\Model\Localization;

trait CourseTrait
{
    public function createCourse(string $title = 'Sulu is awesome', string $locale = 'en'): Course
    {
        $course = new Course();
        $course->setCurrentLocalization(new Localization($locale));
        $course->setTitle($title);

        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush();

        return $course;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
