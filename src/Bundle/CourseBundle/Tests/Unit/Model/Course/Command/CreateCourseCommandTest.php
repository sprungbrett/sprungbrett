<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\CreateCourseCommand;
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
        $command = new CreateCourseCommand('de', ['title' => 'Sprungbrett']);

        $this->assertEquals('Sprungbrett', $command->getTitle());
    }

    public function testGetTitleNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new CreateCourseCommand('de', []);

        $command->getTitle();
    }

    public function testGetDescription()
    {
        $command = new CreateCourseCommand('de', ['description' => 'Sprungbrett is awesome']);

        $this->assertEquals('Sprungbrett is awesome', $command->getDescription());
    }

    public function testGetDescriptionNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new CreateCourseCommand('de', []);

        $command->getDescription();
    }

    public function testGetTrainer()
    {
        $command = new CreateCourseCommand('de', ['trainer' => ['id' => 42]]);

        $this->assertEquals(['id' => 42], $command->getTrainer());
    }

    public function testGetTrainerNull()
    {
        $command = new CreateCourseCommand('de', ['trainer' => null]);

        $this->assertNull($command->getTrainer());
    }
}
