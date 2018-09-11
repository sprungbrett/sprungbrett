<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\TranslatableInterface;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

interface AttendeeInterface extends TranslatableInterface
{
    public function __construct(ContactInterface $contact, ?array $translations = null);

    public function getId(): int;

    public function getFullName(): string;

    public function getDescription(): string;

    public function setDescription(string $description): self;

    public function bookmark(CourseInterface $course): self;

    public function removeBookmark(CourseInterface $course): self;

    /**
     * @return CourseInterface[]
     */
    public function getBookmarks(): array;
}
