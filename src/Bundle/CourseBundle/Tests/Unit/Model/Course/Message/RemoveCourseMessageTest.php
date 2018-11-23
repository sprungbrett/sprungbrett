<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Course\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\RemoveCourseMessage;

class RemoveCourseMessageTest extends TestCase
{
    /**
     * @var RemoveCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new RemoveCourseMessage('123-123-123');
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->message->getUuid());
    }
}
