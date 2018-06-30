<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Command\RemoveCourseCommand;

class RemoveCourseCommandTest extends TestCase
{
    public function testGetId()
    {
        $command = new RemoveCourseCommand('123-123-123');

        $this->assertEquals('123-123-123', $command->getId());
    }
}
