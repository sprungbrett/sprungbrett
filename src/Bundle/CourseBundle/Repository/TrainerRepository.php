<?php

namespace Sprungbrett\Bundle\CourseBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Trainer;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Api\Contact;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

class TrainerRepository extends EntityRepository implements TrainerRepositoryInterface
{
    public function findOrCreateTrainerById(int $id, Localization $localization): TrainerInterface
    {
        /** @var TrainerInterface $trainer */
        $trainer = $this->find($id);
        if (!$trainer) {
            /** @var ContactInterface $contact */
            $contact = $this->getEntityManager()->getReference(ContactInterface::class, $id);

            $class = $this->getClassName();
            $trainer = new $class($contact);
            $this->getEntityManager()->persist($trainer);
        }

        $trainer->setCurrentLocalization($localization);

        return $trainer;
    }
}
