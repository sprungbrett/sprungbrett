<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Trainer\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Command\ModifyTrainerCommand;
use Sprungbrett\Component\Translation\Model\Localization;

class ModifyTrainerCommandTest extends TestCase
{
    public function testGetId()
    {
        $command = new ModifyTrainerCommand(42, 'de', ['description' => 'Sprungbrett is awesome']);

        $this->assertEquals(42, $command->getId());
    }

    public function testGetLocalization()
    {
        $command = new ModifyTrainerCommand(42, 'de', ['description' => 'Sprungbrett is awesome']);

        $this->assertInstanceOf(Localization::class, $command->getLocalization());
        $this->assertEquals('de', $command->getLocalization()->getLocale());
    }

    public function testGetTitle()
    {
        $command = new ModifyTrainerCommand(42, 'de', ['description' => 'Sprungbrett is awesome']);

        $this->assertEquals('Sprungbrett is awesome', $command->getDescription());
    }

    public function testGetDescriptionNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new ModifyTrainerCommand(42, 'de', []);

        $command->getDescription();
    }
}
