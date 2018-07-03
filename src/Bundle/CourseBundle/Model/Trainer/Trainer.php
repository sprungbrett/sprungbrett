<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Trainer;

use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Translation\Model\Exception\MissingLocalizationException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslatableTrait;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class Trainer implements TrainerInterface, AuditableInterface
{
    use AuditableTrait;
    use TranslatableTrait;

    /**
     * @var ContactInterface
     */
    protected $contact;

    public function __construct(ContactInterface $contact, ?Collection $translations = null)
    {
        $this->initializeTranslations($translations);

        $this->contact = $contact;
    }

    public function getContact(): ContactInterface
    {
        return $this->contact;
    }

    public function getId(): int
    {
        return $this->getContact()->getId();
    }

    /**
     * TODO handling of academic title.
     */
    public function getFullName(): string
    {
        $contact = $this->getContact();

        return implode(' ', [$contact->getFirstName(), $contact->getMiddleName(), $contact->getLastName()]);
    }

    public function getDescription(?Localization $localization = null): string
    {
        return $this->getTranslation($localization)->getDescription();
    }

    public function setDescription(string $description, ?Localization $localization = null): TrainerInterface
    {
        $this->getTranslation($localization)->setDescription($description);

        return $this;
    }

    /**
     * @throws MissingLocalizationException
     */
    public function getTranslation(?Localization $localization = null): TrainerTranslationInterface
    {
        /** @var TrainerTranslation $translation */
        $translation = $this->doGetTranslation($localization);

        return $translation;
    }

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new TrainerTranslation($this, $localization);
    }
}
