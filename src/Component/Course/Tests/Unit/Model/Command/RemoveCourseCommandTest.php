<?php

namespace Sprungbrett\Component\Course\Tests\Unit\Model\Command;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Course\Model\Command\RemoveCourseCommand;
use Sprungbrett\Component\Uuid\Model\Uuid;

class RemoveCourseCommandTest extends TestCase
{
    public function testGetUuid()
    {
        $command = new RemoveCourseCommand('123-123-123');

        $this->assertInstanceOf(Uuid::class, $command->getUuid());
        $this->assertEquals('123-123-123', $command->getUuid()->getId());
    }
}
