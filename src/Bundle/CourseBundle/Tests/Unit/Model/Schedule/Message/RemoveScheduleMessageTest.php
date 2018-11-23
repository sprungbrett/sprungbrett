<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\RemoveScheduleMessage;

class RemoveScheduleMessageTest extends TestCase
{
    /**
     * @var RemoveScheduleMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new RemoveScheduleMessage('123-123-123');
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->message->getUuid());
    }
}
