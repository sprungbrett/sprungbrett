<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Exception\CourseNotFoundException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;

class CourseRepository extends EntityRepository implements CourseRepositoryInterface
{
    public function create(?Localization $localization = null, ?Uuid $uuid = null): CourseInterface
    {
        $className = $this->getClassName();

        /** @var CourseInterface $course */
        $course = new $className($uuid);
        if ($localization) {
            $course->setCurrentLocalization($localization);
        }

        $this->getEntityManager()->persist($course);

        return $course;
    }

    public function findByUuid(Uuid $uuid, ?Localization $localization = null): CourseInterface
    {
        /** @var CourseInterface $course */
        $course = $this->find($uuid->getId());
        if (!$course) {
            throw new CourseNotFoundException($uuid);
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
