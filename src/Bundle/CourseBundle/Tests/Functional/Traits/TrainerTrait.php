<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Trainer;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Entity\Contact;

trait TrainerTrait
{
    public function createTrainer(
        string $description = 'Sprungbrett is awesome',
        string $firstName = 'Max',
        string $lastName = 'Mustermann',
        string $locale = 'en'
    ): Trainer {
        $contact = new Contact();
        $contact->setFirstName($firstName);
        $contact->setLastName($lastName);
        $this->getEntityManager()->persist($contact);
        $this->getEntityManager()->flush();

        $trainer = new Trainer($contact);
        $trainer->setCurrentLocalization(new Localization($locale));
        $trainer->setDescription($description);

        $this->getEntityManager()->persist($trainer);
        $this->getEntityManager()->flush();

        return $trainer;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
