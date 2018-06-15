<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sprungbrett\Component\Course\Model\CourseRepositoryInterface;
use Sprungbrett\Component\Course\Model\Exception\CourseNotFoundException;
use Sprungbrett\Component\Uuid\Model\Uuid;

class CourseRepository extends EntityRepository implements CourseRepositoryInterface
{
    public function create(?Uuid $uuid = null): CourseInterface
    {
        $className = $this->getClassName();
        $entity = new $className($uuid);
        $this->getEntityManager()->persist($entity);

        return $entity;
    }

    public function findByUuid(Uuid $uuid): CourseInterface
    {
        /** @var CourseInterface $course */
        $course = $this->find($uuid->getId());
        if (!$course) {
            throw new CourseNotFoundException($uuid);
        }

        return $course;
    }

    public function remove(CourseInterface $course): void
    {
        $this->getEntityManager()->remove($course);
    }
}
