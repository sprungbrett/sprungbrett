<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Ramsey\Uuid\Uuid;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotFoundException;
use Sprungbrett\Component\Translation\Model\Localization;

class CourseRepository extends EntityRepository implements CourseRepositoryInterface
{
    public function create(?Localization $localization = null, ?string $id = null): CourseInterface
    {
        $className = $this->getClassName();

        /** @var CourseInterface $course */
        $course = new $className($id ?: Uuid::uuid4()->toString());
        if ($localization) {
            $course->setCurrentLocalization($localization);
        }

        $this->getEntityManager()->persist($course);

        return $course;
    }

    public function findById(string $id, ?Localization $localization = null): CourseInterface
    {
        /** @var CourseInterface $course */
        $course = $this->find($id);
        if (!$course) {
            throw new CourseNotFoundException($id);
        }

        if ($localization) {
            $course->setCurrentLocalization($localization);
        }

        return $course;
    }

    public function remove(CourseInterface $course): void
    {
        $this->getEntityManager()->remove($course);
    }
}
