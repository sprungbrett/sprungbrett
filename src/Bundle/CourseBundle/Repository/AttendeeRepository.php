<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeRepositoryInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

class AttendeeRepository extends EntityRepository implements AttendeeRepositoryInterface
{
    public function findOrCreateAttendeeById(int $id, Localization $localization): AttendeeInterface
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

        $attendee->setCurrentLocalization($localization);

        return $attendee;
    }
}
