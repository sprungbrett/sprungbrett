<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentPublishedEvent;

class ContentPublishedEventTest extends TestCase
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var ContentPublishedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->content = $this->prophesize(ContentInterface::class);

        $this->event = new ContentPublishedEvent($this->content->reveal());
    }

    public function testGetContent(): void
    {
        $this->assertEquals($this->content->reveal(), $this->event->getContent());
    }
}
