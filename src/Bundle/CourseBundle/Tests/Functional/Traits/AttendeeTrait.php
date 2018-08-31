<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Attendee;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Entity\Contact;

trait AttendeeTrait
{
    public function createAttendee(
        string $description = 'Sprungbrett is awesome',
        string $firstName = 'Max',
        string $lastName = 'Mustermann',
        string $locale = 'en'
    ): Attendee {
        $contact = new Contact();
        $contact->setFirstName($firstName);
        $contact->setLastName($lastName);
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();

        $attendee = new Attendee($contact);
        $attendee->setCurrentLocalization(new Localization($locale));
        $attendee->setDescription($description);

        $this->getEntityManager()->persist($attendee);
        $this->getEntityManager()->flush();

        return $attendee;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
