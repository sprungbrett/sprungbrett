<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Message\PublishCourseMessage;

class PublishCourseMessageTest extends TestCase
{
    /**
     * @var PublishCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new PublishCourseMessage('123-123-123', 'en');
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
