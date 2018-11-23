<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Message\PublishScheduleMessage;

class PublishScheduleMessageTest extends TestCase
{
    /**
     * @var PublishScheduleMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new PublishScheduleMessage('123-123-123', 'en');
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->message->getUuid());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->message->getLocale());
    }
}
