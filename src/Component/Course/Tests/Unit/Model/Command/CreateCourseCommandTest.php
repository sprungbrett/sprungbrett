<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\CreateCourseCommand;

class CreateCourseCommandTest extends TestCase
{
    public function testGetTitle()
    {
        $command = new CreateCourseCommand(['title' => 'Sprungbrett is awesome']);

        $this->assertEquals('Sprungbrett is awesome', $command->getTitle());
    }

    public function testGetTitleNotExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new CreateCourseCommand([]);

        $command->getTitle();
    }
}
