<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\CreateCourseMessage;

class CreateCourseMessageTest extends TestCase
{
    /**
     * @var CreateCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new CreateCourseMessage('en', ['name' => 'Sprungbrett']);
    }

    public function testGetUuid(): void
    {
        $this->assertTrue(is_string($this->message->getUuid()));
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->message->getLocale());
    }

    public function testGetName(): void
    {
        $this->assertEquals('Sprungbrett', $this->message->getName());
    }
}
