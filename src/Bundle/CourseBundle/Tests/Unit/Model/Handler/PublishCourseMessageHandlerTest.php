<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\PublishContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CoursePublishedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\PublishCourseMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\PublishCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class PublishCourseMessageHandlerTest extends TestCase
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var PublishCourseMessageHandler
     */
    private $handler;

    /**
     * @var PublishCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new PublishCourseMessageHandler(
            $this->messageBus->reveal(),
            $this->messageCollector->reveal(),
            $this->courseRepository->reveal()
        );

        $this->message = $this->prophesize(PublishCourseMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
    }

    public function testInvoke(): void
    {
        $draftCourse = $this->prophesize(CourseInterface::class);
        $this->courseRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn($draftCourse->reveal());

        $liveCourse = $this->prophesize(CourseInterface::class);
        $liveCourse->getUuid()->willReturn('123-123-123');
        $this->courseRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($liveCourse->reveal());

        $draftCourse->getName()->willReturn('Sprungbrett');
        $liveCourse->setName('Sprungbrett')->shouldBeCalled();

        $this->messageBus->dispatch(
            Argument::that(
                function (PublishContentMessage $message) {
                    $this->assertEquals('courses', $message->getResourceKey());
                    $this->assertEquals('123-123-123', $message->getResourceId());
                    $this->assertEquals('en', $message->getLocale());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (CoursePublishedEvent $event) use ($draftCourse) {
                    $this->assertEquals('123-123-123', $event->getUuid());
                    $this->assertEquals('en', $event->getLocale());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeCreate(): void
    {
        $draftCourse = $this->prophesize(CourseInterface::class);
        $this->courseRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn($draftCourse->reveal());

        $liveCourse = $this->prophesize(CourseInterface::class);
        $liveCourse->getUuid()->willReturn('123-123-123');
        $this->courseRepository->findById('123-123-123', Stages::LIVE, 'en')->willReturn(null);
        $this->courseRepository->create('123-123-123', Stages::LIVE, 'en')->willReturn($liveCourse->reveal());

        $draftCourse->getName()->willReturn('Sprungbrett');
        $liveCourse->setName('Sprungbrett')->shouldBeCalled();

        $this->messageBus->dispatch(
            Argument::that(
                function (PublishContentMessage $message) {
                    $this->assertEquals('courses', $message->getResourceKey());
                    $this->assertEquals('123-123-123', $message->getResourceId());
                    $this->assertEquals('en', $message->getLocale());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (CoursePublishedEvent $event) use ($draftCourse) {
                    $this->assertEquals('123-123-123', $event->getUuid());
                    $this->assertEquals('en', $event->getLocale());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }

    public function testInvokeNotFound(): void
    {
        $this->expectException(CourseNotFoundException::class);

        $this->courseRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn(null);

        $this->messageCollector->push(Argument::any())->shouldNotBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
