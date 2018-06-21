<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\PublishCourseCommand;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Uuid\Model\Uuid;

class PublishCourseCommandTest extends TestCase
{
    public function testGetUuid()
    {
        $command = new PublishCourseCommand('123-123-123', 'de');

        $this->assertInstanceOf(Uuid::class, $command->getUuid());
        $this->assertEquals('123-123-123', $command->getUuid()->getId());
    }

    public function testGetLocalization()
    {
        $command = new PublishCourseCommand('123-123-123', 'de');

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }
}