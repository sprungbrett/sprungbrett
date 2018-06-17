<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;
use Sprungbrett\Component\Translation\Model\Localization;

class CreateCourseCommandTest extends TestCase
{
    public function testGetLocalization()
    {
        $command = new CreateCourseCommand('de', []);

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }

    public function testGetTitle()
    {
        $command = new CreateCourseCommand('de', ['title' => 'Sprungbrett is awesome']);

        $this->assertEquals('Sprungbrett is awesome', $command->getTitle());
    }

    public function testGetTitleNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new CreateCourseCommand('de', []);

        $command->getTitle();
    }
}
