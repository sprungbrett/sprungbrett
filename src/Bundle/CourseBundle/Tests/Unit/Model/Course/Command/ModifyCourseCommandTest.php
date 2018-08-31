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

    public function testGetName()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', ['name' => 'Sprungbrett']);

        $this->assertEquals('Sprungbrett', $command->getName());
    }

    public function testGetNameNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new ModifyCourseCommand('123-123-123', 'de', []);

        $command->getName();
    }

    public function testGetDescription()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', ['description' => 'Sprungbrett is awesome']);

        $this->assertEquals('Sprungbrett is awesome', $command->getDescription());
    }

    public function testGetDescriptionNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new ModifyCourseCommand('123-123-123', 'de', []);

        $command->getDescription();
    }

    public function testGetTrainer()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', ['trainer' => ['id' => 42]]);

        $this->assertEquals(['id' => 42], $command->getTrainer());
    }

    public function testGetTrainerNull()
    {
        $command = new ModifyCourseCommand('123-123-123', 'de', ['trainer' => null]);

        $this->assertNull($command->getTrainer());
    }
}
