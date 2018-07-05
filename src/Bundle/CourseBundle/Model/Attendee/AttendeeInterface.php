<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Doctrine\Common\Collections\Collection;
use Sprungbrett\Component\Translation\Model\TranslatableInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

interface AttendeeInterface extends TranslatableInterface
{
    public function __construct(ContactInterface $contact, ?Collection $translations = null);

    public function getId(): int;

    public function getFullName(): string;

    public function getDescription(): string;

    public function setDescription(string $description): self;
}
