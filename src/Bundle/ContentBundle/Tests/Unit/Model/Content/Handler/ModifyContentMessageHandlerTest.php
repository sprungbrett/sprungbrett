<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentModifiedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Exception\ContentNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Handler\ModifyContentMessageHandler;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\ModifyContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifyContentMessageHandlerTest extends TestCase
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
     * @var ModifyContentMessageHandler
     */
    private $handler;

    /**
     * @var ModifyContentMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->contentRepository = $this->prophesize(ContentRepositoryInterface::class);

        $this->handler = new ModifyContentMessageHandler(
            $this->messageCollector->reveal(),
            $this->contentRepository->reveal()
        );

        $this->message = $this->prophesize(ModifyContentMessage::class);
        $this->message->getResourceKey()->willReturn('courses');
        $this->message->getResourceId()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
        $this->message->getType()->willReturn('default');
        $this->message->getData()->willReturn(['title' => 'Sprungbrett']);
    }

    public function testInvoke(): void
    {
        $content = $this->prophesize(ContentInterface::class);
        $this->contentRepository->findByResource('courses', '123-123-123', Stages::DRAFT, 'en')
            ->willReturn($content->reveal());

        $content->modifyData('default', ['title' => 'Sprungbrett'])->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (ContentModifiedEvent $event) use ($content) {
                    return $event->getContent() === $content->reveal();
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
