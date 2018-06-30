<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Symfony\Component\Workflow\Workflow;

trait CourseTrait
{
    public function createCourse(
        string $title = 'Sprungbrett',
        string $description = 'Sprungbrett is awesome',
        string $locale = 'en',
        string $structureType = 'default'
    ): Course {
        $course = new Course(Uuid::uuid4()->toString());
        $course->setCurrentLocalization(new Localization($locale));
        $course->setTitle($title);
        $course->setDescription($description);
        $course->setStructureType($structureType);

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
