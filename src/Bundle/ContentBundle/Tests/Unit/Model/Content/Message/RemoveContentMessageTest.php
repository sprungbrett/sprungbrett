<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\RemoveContentMessage;

class RemoveContentMessageTest extends TestCase
{
    /**
     * @var RemoveContentMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new RemoveContentMessage('courses', '123-123-123');
    }

    public function testGetResourceKey(): void
    {
        $this->assertEquals('courses', $this->message->getResourceKey());
    }

    public function testGetResourceId(): void
    {
        $this->assertEquals('123-123-123', $this->message->getResourceId());
    }
}
