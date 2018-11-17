<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\ContentBundle\Tests\Functional\Traits\ContentTrait;
use Sprungbrett\Bundle\CourseBundle\Model\Course;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;

trait CourseTrait
{
    use ContentTrait;

    public function createCourse(string $name = 'Sprungbrett', string $locale = 'en'): CourseInterface
    {
        $course = new Course(Uuid::uuid4()->toString(), Stages::DRAFT);
        $course->setCurrentLocale($locale);
        $course->setName($name);

        $this->createContent('courses', $course->getUuid());

        $this->getEntityManager()->persist($course);
        $this->getEntityManager()->flush();

        return $course;
    }

    public function findCourse(string $uuid, ?string $locale = null, string $stage = Stages::DRAFT): ?CourseInterface
    {
        $repository = $this->getEntityManager()->getRepository(Course::class);

        /** @var CourseInterface|null $course */
        $course = $repository->findOneBy(['uuid' => $uuid, 'stage' => $stage]);
        if ($course && $locale) {
            $course->setCurrentLocale($locale);
        }

        return $course;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
