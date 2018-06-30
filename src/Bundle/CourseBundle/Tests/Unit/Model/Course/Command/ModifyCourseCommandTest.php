<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\ModifyCourseCommand;
use Sprungbrett\Component\Translation\Model\Localization;

class ModifyCourseCommandTest extends TestCase
{
    public function testGetId()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', ['title' => 'Sprungbrett is awesome']);

        $this->assertEquals('123-123-123', $command->getId());
    }

    public function testGetLocalization()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', []);

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }

    public function testGetTitle()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', ['title' => 'Sprungbrett is awesome']);

        $this->assertEquals('Sprungbrett is awesome', $command->getTitle());
    }

    public function testGetTitleNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new ModifyCourseCommand('123-123-123', 'de', []);

        $command->getTitle();
    }
}
