<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Attendee;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeTranslationInterfae;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

class AttendeeTest extends TestCase
{
    public function testGetContact()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal());

        $this->assertEquals($contact->reveal(), $attendee->getContact());
    }

    public function testGetId()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal());

        $this->assertEquals(42, $attendee->getId());
    }

    public function testGetFullName()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $contact->getFirstName()->willReturn('Max');
        $contact->getLastName()->willReturn('Mustermann');
        $contact->getMiddleName()->willReturn('"the power"');
        $attendee = new Attendee($contact->reveal());

        $this->assertEquals('Max "the power" Mustermann', $attendee->getFullName());
    }

    public function testGetLocale()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(AttendeeTranslationInterfae::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getLocale()->willReturn('de');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal(), [$translation->reveal()]);
        $attendee->setCurrentLocalization($localization->reveal());

        $this->assertEquals('de', $attendee->getLocale());
    }

    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);

        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal());
        $attendee->setCurrentLocalization($localization->reveal());

        $this->assertEquals($localization->reveal(), $attendee->getLocalization());
    }

    public function testGetLocaleWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(AttendeeTranslationInterfae::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getLocale()->willReturn('de');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal(), [$translation->reveal()]);

        $this->assertEquals('de', $attendee->getLocale($localization->reveal()));
    }

    public function testGetDescription()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(AttendeeTranslationInterfae::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getDescription()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal(), [$translation->reveal()]);
        $attendee->setCurrentLocalization($localization->reveal());

        $this->assertEquals('Sprungbrett is awesome', $attendee->getDescription());
    }

    public function testGetDescriptionWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(AttendeeTranslationInterfae::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getDescription()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal(), [$translation->reveal()]);

        $this->assertEquals('Sprungbrett is awesome', $attendee->getDescription($localization->reveal()));
    }

    public function testBookmark()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal());

        $course = $this->prophesize(CourseInterface::class);

        $this->assertEquals($attendee, $attendee->bookmark($course->reveal()));
        $this->assertEquals([$course->reveal()], $attendee->getBookmarks());
    }

    public function testBookmarkTwice()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $attendee = new Attendee($contact->reveal());

        $course = $this->prophesize(CourseInterface::class);

        $this->assertEquals($attendee, $attendee->bookmark($course->reveal()));
        $this->assertEquals($attendee, $attendee->bookmark($course->reveal()));
        $this->assertEquals([$course->reveal()], $attendee->getBookmarks());
    }
}
