<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\PublishContentMessage;

class PublishContentMessageTest extends TestCase
{
    /**
     * @var PublishContentMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new PublishContentMessage('courses', '123-123-123', 'en');
    }

    public function testGetResourceKey(): void
    {
        $this->assertEquals('courses', $this->message->getResourceKey());
    }

    public function testGetResourceId(): void
    {
        $this->assertEquals('123-123-123', $this->message->getResourceId());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->message->getLocale());
    }
}
