<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\CreateContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Event\CourseCreatedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Handler\CreateCourseMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Message\CreateCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateCourseMessageHandlerTest extends TestCase
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
     * @var CreateCourseMessageHandler
     */
    private $handler;

    /**
     * @var CreateCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageBus = $this->prophesize(MessageBusInterface::class);
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new CreateCourseMessageHandler(
            $this->messageBus->reveal(),
            $this->messageCollector->reveal(),
            $this->courseRepository->reveal()
        );

        $this->message = $this->prophesize(CreateCourseMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
        $this->message->getName()->willReturn('Sprungbrett');
    }

    public function testInvoke(): void
    {
        $course = $this->prophesize(CourseInterface::class);
        $this->courseRepository->create('123-123-123', Stages::DRAFT, 'en')->willReturn($course->reveal());

        $course->setName('Sprungbrett')->shouldBeCalled();

        $this->messageBus->dispatch(
            Argument::that(
                function (CreateContentMessage $message) {
                    $this->assertEquals('courses', $message->getResourceKey());
                    $this->assertEquals('123-123-123', $message->getResourceId());
                    $this->assertEquals('en', $message->getLocale());
                    $this->assertEquals('default', $message->getType());
                    $this->assertEquals([], $message->getData());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (CourseCreatedEvent $event) use ($course) {
                    $this->assertEquals($course->reveal(), $event->getCourse());

                    return true;
                }
            )
        )->shouldBeCalled();

        $this->handler->__invoke($this->message->reveal());
    }
}
