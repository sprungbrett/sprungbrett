<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\UnpublishCourseCommand;
use Sprungbrett\Component\Translation\Model\Localization;

class UnpublishCourseCommandTest extends TestCase
{
    public function testGetId()
    {
        $command = new UnpublishCourseCommand('123-123-123', 'de');

        $this->assertEquals('123-123-123', $command->getId());
    }

    public function testGetLocalization()
    {
        $command = new UnpublishCourseCommand('123-123-123', 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}
