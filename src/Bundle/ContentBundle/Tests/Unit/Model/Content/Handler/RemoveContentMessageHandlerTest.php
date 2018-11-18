<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentRemovedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Handler\RemoveContentMessageHandler;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\RemoveContentMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class RemoveContentMessageHandlerTest extends TestCase
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var ContentRepositoryInterface
     */
    private $contentRepository;

    /**
     * @var RemoveContentMessageHandler
     */
    private $handler;

    /**
     * @var RemoveContentMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->contentRepository = $this->prophesize(ContentRepositoryInterface::class);

        $this->handler = new RemoveContentMessageHandler(
            $this->messageCollector->reveal(),
            $this->contentRepository->reveal()
        );

        $this->message = $this->prophesize(RemoveContentMessage::class);
        $this->message->getResourceKey()->willReturn('courses');
        $this->message->getResourceId()->willReturn('123-123-123');
    }

    public function testInvoke(): void
    {
        $draftContent = $this->prophesize(ContentInterface::class);
        $liveContent = $this->prophesize(ContentInterface::class);
        $this->contentRepository->findAllByResource('courses', '123-123-123')
            ->willReturn([$draftContent->reveal(), $liveContent->reveal()]);

        $this->contentRepository->remove($draftContent->reveal())->shouldBeCalled();
        $this->contentRepository->remove($liveContent->reveal())->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ContentRemovedEvent $event) use ($draftContent) {
                    return $event->getContent() === $draftContent->reveal();
                }
            )
        )->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ContentRemovedEvent $event) use ($liveContent) {
                    return $event->getContent() === $liveContent->reveal();
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
