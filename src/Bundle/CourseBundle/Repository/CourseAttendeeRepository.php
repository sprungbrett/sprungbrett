<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\CourseAttendeeRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

class CourseAttendeeRepository extends EntityRepository implements CourseAttendeeRepositoryInterface
{
    public function findOrCreateCourseAttendeeById(int $attendeeId, string $courseId): CourseAttendeeInterface
    {
        /** @var CourseAttendeeInterface $courseAttendee */
        $courseAttendee = $this->find(['attendee' => $attendeeId, 'course' => $courseId]);
        if ($courseAttendee) {
            return $courseAttendee;
        }

        $class = $this->getClassName();

        $entityManager = $this->getEntityManager();

        /** @var CourseInterface $course */
        $course = $entityManager->getReference(CourseInterface::class, $courseId);

        /** @var AttendeeInterface $attendee */
        $attendee = $entityManager->getReference(AttendeeInterface::class, $courseId);

        $courseAttendee = new $class($attendee, $course);
        $this->getEntityManager()->persist($courseAttendee);

        return $courseAttendee;
    }

    public function countByCourse(string $courseId): int
    {
        $queryBuilder = $this->createQueryBuilder('course_attendee')
            ->select('COUNT(IDENTITY(course_attendee))')
            ->where('IDENTITY(course_attendee.course) = :courseId')
            ->setParameter('courseId', $courseId);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function exists(int $attendeeId, string $courseId): bool
    {
        $queryBuilder = $this->createQueryBuilder('course_attendee')
            ->select('COUNT(IDENTITY(course_attendee))')
            ->where('IDENTITY(course_attendee.attendee) = :attendeeId')
            ->andWhere('IDENTITY(course_attendee.course) = :courseId')
            ->setParameter('attendeeId', $attendeeId)
            ->setParameter('courseId', $courseId);

        return 0 < $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
