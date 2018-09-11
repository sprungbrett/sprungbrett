<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\Exception\MissingLocalizationException;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslatableTrait;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class Attendee implements AttendeeInterface, AuditableInterface
{
    use AuditableTrait;
    use TranslatableTrait;

    /**
     * @var ContactInterface
     */
    protected $contact;

    /**
     * @var int
     */
    protected $contactId;

    /**
     * @var CourseInterface[]|Collection
     */
    protected $bookmarks;

    public function __construct(ContactInterface $contact, ?array $translations = null)
    {
        $this->initializeTranslations($translations);

        $this->contact = $contact;
        $this->contactId = $contact->getId();

        $this->bookmarks = new ArrayCollection();
    }

    public function getContact(): ContactInterface
    {
        return $this->contact;
    }

    public function getId(): int
    {
        return $this->contactId;
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

    public function setDescription(string $description, ?Localization $localization = null): AttendeeInterface
    {
        $this->getTranslation($localization)->setDescription($description);

        return $this;
    }

    public function bookmark(CourseInterface $course): AttendeeInterface
    {
        if ($this->bookmarks->contains($course)) {
            return $this;
        }

        $this->bookmarks->add($course);

        return $this;
    }

    public function getBookmarks(): array
    {
        return $this->bookmarks->getValues();
    }

    /**
     * @throws MissingLocalizationException
     */
    public function getTranslation(?Localization $localization = null): AttendeeTranslationInterfae
    {
        /** @var AttendeeTranslationInterfae $translation */
        $translation = $this->doGetTranslation($localization);

        return $translation;
    }

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new AttendeeTranslation($this, $localization);
    }
}
