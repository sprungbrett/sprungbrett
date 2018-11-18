<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Message\ModifyCourseMessage;

class ModifyCourseMessageTest extends TestCase
{
    /**
     * @var ModifyCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new ModifyCourseMessage('123-123-123', 'en', ['name' => 'Sprungbrett']);
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->message->getUuid());
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
