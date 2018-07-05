<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeTranslation;
use Sprungbrett\Component\Translation\Model\Localization;

class AttendeeTranslationTest extends TestCase
{
    public function testGetAttendee()
    {
        $localization = $this->prophesize(Localization::class);
        $attendee = $this->prophesize(AttendeeInterface::class);

        $translation = new AttendeeTranslation($attendee->reveal(), $localization->reveal());

        $this->assertEquals($attendee->reveal(), $translation->getAttendee());
    }

    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $attendee = $this->prophesize(AttendeeInterface::class);

        $translation = new AttendeeTranslation($attendee->reveal(), $localization->reveal());

        $this->assertEquals($localization->reveal(), $translation->getLocalization());
    }

    public function testGetDescription()
    {
        $localization = $this->prophesize(Localization::class);
        $attemdee = $this->prophesize(AttendeeInterface::class);

        $translation = new AttendeeTranslation($attemdee->reveal(), $localization->reveal());

        $translation->setDescription('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $translation->getDescription());
    }
}
