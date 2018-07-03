<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Trainer\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Query\FindTrainerQuery;
use Sprungbrett\Component\Translation\Model\Localization;

class FindTrainerQueryTest extends TestCase
{
    public function getId()
    {
        $command = new FindTrainerQuery(42, 'de');

        $this->assertEquals(42, $command->getId());
    }

    public function testGetLocale()
    {
        $command = new FindTrainerQuery(42, 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}
