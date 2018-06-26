<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\RemoveCourseCommand;

class RemoveCourseCommandTest extends TestCase
{
    public function testGetId()
    {
        $command = new RemoveCourseCommand('123-123-123');

        $this->assertEquals('123-123-123', $command->getId());
    }
}
