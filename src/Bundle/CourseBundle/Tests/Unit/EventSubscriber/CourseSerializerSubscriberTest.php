<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\EventSubscriber;

use JMS\Serializer\Context;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use League\Tactician\CommandBus;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Sprungbrett\Bundle\CourseBundle\EventSubscriber\CourseSerializeSubscriber;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Query\FindTrainerQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class CourseSerializerSubscriberTest extends TestCase
{
    public function testOnSerializeAppendTransitions()
    {
        $workflow = $this->prophesize(Workflow::class);
        $commandBus = $this->prophesize(CommandBus::class);

        $subscriber = new CourseSerializeSubscriber($workflow->reveal(), $commandBus->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $visitor = $this->prophesize(JsonSerializationVisitor::class);
        $context = $this->prophesize(Context::class);

        $event = $this->prophesize(ObjectEvent::class);
        $event->getObject()->willReturn($course->reveal());
        $event->getVisitor()->willReturn($visitor->reveal());
        $event->getContext()->willReturn($context->reveal());

        $transitions = [$this->prophesize(Transition::class)->reveal()];

        $workflow->getEnabledTransitions($course->reveal())->willReturn($transitions);
        $context->accept($transitions)->willReturn([['name' => 'publish']]);

        $visitor->setData('transitions', [['name' => 'publish']])->shouldBeCalled();

        $subscriber->onSerializeAppendTransitions($event->reveal());
    }

    public function testOnSerializeAppendTrainer()
    {
        $workflow = $this->prophesize(Workflow::class);
        $commandBus = $this->prophesize(CommandBus::class);

        $subscriber = new CourseSerializeSubscriber($workflow->reveal(), $commandBus->reveal());

        $course = $this->prophesize(CourseInterface::class);
        $visitor = $this->prophesize(JsonSerializationVisitor::class);
        $context = $this->prophesize(Context::class);

        $event = $this->prophesize(ObjectEvent::class);
        $event->getObject()->willReturn($course->reveal());
        $event->getVisitor()->willReturn($visitor->reveal());
        $event->getContext()->willReturn($context->reveal());

        $course->getLocale()->willReturn('de');
        $course->getTrainerId()->willReturn(42);

        $trainer = $this->prophesize(TrainerInterface::class);
        $commandBus->handle(
            Argument::that(
                function (FindTrainerQuery $query) {
                    return 42 === $query->getId() && 'de' === $query->getLocalization()->getLocale();
                }
            )
        )->willReturn($trainer->reveal());

        $context->accept($trainer)->willReturn(['id' => 42]);

        $visitor->setData('trainer', ['id' => 42])->shouldBeCalled();

        $subscriber->onSerializeAppendTrainer($event->reveal());
    }
}
