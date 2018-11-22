<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\RemoveContentMessage;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseRemovedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\RemoveCourseMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\RemoveCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class RemoveCourseMessageHandlerTest extends TestCase
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
     * @var RemoveCourseMessageHandler
     */
    private $handler;

    /**
     * @var RemoveCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new RemoveCourseMessageHandler(
            $this->messageBus->reveal(),
            $this->messageCollector->reveal(),
            $this->courseRepository->reveal()
        );

        $this->message = $this->prophesize(RemoveCourseMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
    }

    public function testInvoke(): void
    {
        $draftCourse = $this->prophesize(CourseInterface::class);
        $liveCourse = $this->prophesize(CourseInterface::class);

        $this->courseRepository->findAllById('123-123-123')
            ->willReturn([$draftCourse->reveal(), $liveCourse->reveal()]);

        $this->courseRepository->remove($draftCourse->reveal())->shouldBeCalled();
        $this->courseRepository->remove($liveCourse->reveal())->shouldBeCalled();

        $this->messageBus->dispatch(
            Argument::that(
                function (RemoveContentMessage $message) {
                    $this->assertEquals('courses', $message->getResourceKey());
                    $this->assertEquals('123-123-123', $message->getResourceId());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (CourseRemovedEvent $event) use ($draftCourse) {
                    $this->assertEquals('123-123-123', $event->getUuid());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
