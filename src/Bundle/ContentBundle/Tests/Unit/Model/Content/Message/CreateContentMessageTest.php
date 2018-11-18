<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Message;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\CreateContentMessage;

class CreateContentMessageTest extends TestCase
{
    /**
     * @var CreateContentMessage
     */
    private $message;

    protected function setUp()
    {
        $this->message = new CreateContentMessage(
            'courses',
            '123-123-123',
            'en',
            ['type' => 'default', 'data' => ['title' => 'Sprungbrett']]
        );
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

    public function testGetType(): void
    {
        $this->assertEquals('default', $this->message->getType());
    }

    public function testGetData(): void
    {
        $this->assertEquals(['title' => 'Sprungbrett'], $this->message->getData());
    }
}
