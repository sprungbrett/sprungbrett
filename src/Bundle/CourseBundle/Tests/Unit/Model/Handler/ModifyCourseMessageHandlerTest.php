<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Handler;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\CourseRepositoryInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Event\CourseModifiedEvent;
use Sprungbrett\Bundle\CourseBundle\Model\Exception\CourseNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Handler\ModifyCourseMessageHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Message\ModifyCourseMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifyCourseMessageHandlerTest extends TestCase
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    /**
     * @var ModifyCourseMessageHandler
     */
    private $handler;

    /**
     * @var ModifyCourseMessage
     */
    private $message;

    protected function setUp()
    {
        $this->messageCollector = $this->prophesize(MessageCollector::class);
        $this->courseRepository = $this->prophesize(CourseRepositoryInterface::class);

        $this->handler = new ModifyCourseMessageHandler(
            $this->messageCollector->reveal(),
            $this->courseRepository->reveal()
        );

        $this->message = $this->prophesize(ModifyCourseMessage::class);
        $this->message->getUuid()->willReturn('123-123-123');
        $this->message->getLocale()->willReturn('en');
        $this->message->getName()->willReturn('Sprungbrett');
    }

    public function testInvoke(): void
    {
        $course = $this->prophesize(CourseInterface::class);
        $this->courseRepository->findById('123-123-123', Stages::DRAFT, 'en')->willReturn($course->reveal());

        $course->setName('Sprungbrett')->shouldBeCalled();

        $this->messageCollector->push(
            Argument::that(
                function (CourseModifiedEvent $event) use ($course) {
                    $this->assertEquals($course->reveal(), $event->getCourse());

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
