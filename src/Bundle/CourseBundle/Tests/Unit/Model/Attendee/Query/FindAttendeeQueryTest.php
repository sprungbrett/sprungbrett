<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Attendee\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Query\FindAttendeeQuery;
use Sprungbrett\Component\Translation\Model\Localization;

class FindAttendeeQueryTest extends TestCase
{
    public function getId()
    {
        $command = new FindAttendeeQuery(42, 'de');

        $this->assertEquals(42, $command->getId());
    }

    public function testGetLocale()
    {
        $command = new FindAttendeeQuery(42, 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}
