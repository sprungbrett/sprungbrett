<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

class AttendeeRepository extends EntityRepository implements AttendeeRepositoryInterface
{
    public function findOrCreateAttendeeById(int $id, ?Localization $localization = null): AttendeeInterface
    {
        /** @var AttendeeInterface $attendee */
        $attendee = $this->find($id);
        if (!$attendee) {
            /** @var ContactInterface $contact */
            $contact = $this->getEntityManager()->getReference(ContactInterface::class, $id);

            $class = $this->getClassName();
            $attendee = new $class($contact);
            $this->getEntityManager()->persist($attendee);
        }

        if ($localization) {
            $attendee->setCurrentLocalization($localization);
        }

        return $attendee;
    }

    public function countBookmarks(string $courseId): int
    {
        $queryBuilder = $this->createQueryBuilder('attendee')
            ->select('COUNT(attendee.id)')
            ->where('IDENTITY(attendee.course) = :courseId')
            ->setParameter('courseId', $courseId);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function hasBookmark(int $id, string $courseId): bool
    {
        $queryBuilder = $this->createQueryBuilder('attendee')
            ->select('COUNT(attendee.id)')
            ->where('attendee.id = :attendeeId')
            ->andWhere('IDENTITY(attendee.course) = :courseId')
            ->setParameter('attendeeId', $id)
            ->setParameter('courseId', $courseId);

        $result = (int) $queryBuilder->getQuery()->getSingleScalarResult();

        return $result > 0;
    }
}
