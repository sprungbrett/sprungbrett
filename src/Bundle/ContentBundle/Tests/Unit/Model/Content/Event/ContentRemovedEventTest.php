<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Event;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentRemovedEvent;

class ContentRemovedEventTest extends TestCase
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var ContentRemovedEvent
     */
    private $event;

    protected function setUp()
    {
        $this->content = $this->prophesize(ContentInterface::class);

        $this->event = new ContentRemovedEvent($this->content->reveal());
    }

    public function testGetContent(): void
    {
        $this->assertEquals($this->content->reveal(), $this->event->getContent());
    }
}
