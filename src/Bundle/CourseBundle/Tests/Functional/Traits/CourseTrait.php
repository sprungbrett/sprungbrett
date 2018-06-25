<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Symfony\Component\Workflow\Workflow;

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

        $this->getWorkflow()->apply($course, CourseInterface::TRANSITION_CREATE);

        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush();

        return $course;
    }

    public function publish(CourseInterface $course): void
    {
        $this->getWorkflow()->apply($course, CourseInterface::TRANSITION_PUBLISH);
        $this->getEntityManager()->flush();
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();

    abstract public function getWorkflow(): Workflow;
}
