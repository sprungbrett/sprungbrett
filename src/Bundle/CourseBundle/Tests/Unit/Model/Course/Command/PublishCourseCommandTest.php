<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\PublishCourseCommand;
use Sprungbrett\Component\Translation\Model\Localization;

class PublishCourseCommandTest extends TestCase
{
    public function testGetId()
    {
        $command = new PublishCourseCommand('123-123-123', 'de');

        $this->assertEquals('123-123-123', $command->getId());
    }

    public function testGetLocalization()
    {
        $command = new PublishCourseCommand('123-123-123', 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}
