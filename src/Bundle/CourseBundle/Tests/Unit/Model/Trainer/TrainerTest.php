<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Trainer;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Trainer;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerTranslationInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sulu\Bundle\ContactBundle\Entity\ContactInterface;

class TrainerTest extends TestCase
{
    public function testGetContact()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $course = new Trainer($contact->reveal());

        $this->assertEquals($contact->reveal(), $course->getContact());
    }

    public function testGetId()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getId()->willReturn(42);
        $course = new Trainer($contact->reveal());

        $this->assertEquals(42, $course->getId());
    }

    public function testGetFullName()
    {
        $contact = $this->prophesize(ContactInterface::class);
        $contact->getFirstName()->willReturn('Max');
        $contact->getLastName()->willReturn('Mustermann');
        $contact->getMiddleName()->willReturn('"the power"');
        $course = new Trainer($contact->reveal());

        $this->assertEquals('Max "the power" Mustermann', $course->getFullName());
    }

    public function testGetLocale()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(TrainerTranslationInterface::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getLocale()->willReturn('de');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $course = new Trainer($contact->reveal(), new ArrayCollection([$translation->reveal()]));
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals('de', $course->getLocale());
    }

    public function testGetLocaleWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(TrainerTranslationInterface::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getLocale()->willReturn('de');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $course = new Trainer($contact->reveal(), new ArrayCollection([$translation->reveal()]));

        $this->assertEquals('de', $course->getLocale($localization->reveal()));
    }

    public function testGetDescription()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(TrainerTranslationInterface::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getDescription()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $course = new Trainer($contact->reveal(), new ArrayCollection([$translation->reveal()]));
        $course->setCurrentLocalization($localization->reveal());

        $this->assertEquals('Sprungbrett is awesome', $course->getDescription());
    }

    public function testGetDescriptionWithLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $translationLocalization = $this->prophesize(Localization::class);

        $translation = $this->prophesize(TrainerTranslationInterface::class);
        $translation->getLocalization()->willReturn($translationLocalization->reveal());
        $translation->getDescription()->willReturn('Sprungbrett is awesome');

        $localization->equals($translationLocalization->reveal())->willReturn(true);

        $contact = $this->prophesize(ContactInterface::class);
        $course = new Trainer($contact->reveal(), new ArrayCollection([$translation->reveal()]));

        $this->assertEquals('Sprungbrett is awesome', $course->getDescription($localization->reveal()));
    }
}
