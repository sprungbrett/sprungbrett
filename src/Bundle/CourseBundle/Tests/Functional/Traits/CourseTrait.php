<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Sprungbrett\Bundle\CourseBundle\Model\Course;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;

trait CourseTrait
{
    public function createCourse(string $name = 'Sprungbrett', string $locale = 'en'): CourseInterface
    {
        $course = new Course(Uuid::uuid4()->toString());
        $course->setCurrentLocale($locale);
        $course->setName($name);

        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush();

        return $course;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
