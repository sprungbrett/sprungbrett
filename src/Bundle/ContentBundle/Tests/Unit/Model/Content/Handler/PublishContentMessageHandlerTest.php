<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentPublishedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Exception\ContentNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Handler\PublishContentMessageHandler;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\PublishContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class PublishContentMessageHandlerTest extends TestCase
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
     * @var PublishContentMessageHandler
     */
    private $handler;

    /**
     * @var PublishContentMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->contentRepository = $this->prophesize(ContentRepositoryInterface::class);

        $this->handler = new PublishContentMessageHandler(
            $this->messageCollector->reveal(),
            $this->contentRepository->reveal()
        );

        $this->message = $this->prophesize(PublishContentMessage::class);
        $this->message->getResourceKey()->willReturn('courses');
        $this->message->getResourceId()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
    }

    public function testInvoke(): void
    {
        $draftContent = $this->prophesize(ContentInterface::class);
        $draftContent->getType()->willReturn('default');
        $draftContent->getData()->willReturn(['title' => 'Sprungbrett']);
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::DRAFT, 'en')
            ->willReturn($draftContent->reveal());

        $liveContent = $this->prophesize(ContentInterface::class);
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::LIVE, 'en')
            ->willReturn($liveContent->reveal());

        $liveContent->modifyData('default', ['title' => 'Sprungbrett'])->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ContentPublishedEvent $event) use ($liveContent) {
                    return $event->getContent() === $liveContent->reveal();
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeCreate(): void
    {
        $draftContent = $this->prophesize(ContentInterface::class);
        $draftContent->getType()->willReturn('default');
        $draftContent->getData()->willReturn(['title' => 'Sprungbrett']);
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::DRAFT, 'en')
            ->willReturn($draftContent->reveal());

        $liveContent = $this->prophesize(ContentInterface::class);
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::LIVE, 'en')->willReturn(null);
        $this->contentRepository->create('courses', '123-123-123', Stages::LIVE, 'en')
            ->willReturn($liveContent->reveal());

        $liveContent->modifyData('default', ['title' => 'Sprungbrett'])->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ContentPublishedEvent $event) use ($liveContent) {
                    return $event->getContent() === $liveContent->reveal();
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeNotFound(): void
    {
        $this->expectException(ContentNotFoundException::class);

        $this->contentRepository->findByResource('courses', '123-123-123', Stages::DRAFT, 'en')
            ->willReturn(null);

        $this->messageCollector->push(Argument::any())->shouldNotBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
