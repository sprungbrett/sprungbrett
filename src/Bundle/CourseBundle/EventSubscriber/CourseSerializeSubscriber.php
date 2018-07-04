<?php

namespace Sprungbrett\Bundle\CourseBundle\EventSubscriber;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use League\Tactician\CommandBus;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Query\FindTrainerQuery;
use Symfony\Component\Workflow\Workflow;

class CourseSerializeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'method' => 'onSerializeAppendTransitions',
                'class' => Course::class,
                'format' => 'json',
            ],
            [
                'event' => Events::POST_SERIALIZE,
                'method' => 'onSerializeAppendTrainer',
                'class' => Course::class,
                'format' => 'json',
            ],
        ];
    }

    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(Workflow $workflow, CommandBus $commandBus)
    {
        $this->workflow = $workflow;
        $this->commandBus = $commandBus;
    }

    public function onSerializeAppendTransitions(ObjectEvent $event): void
    {
        $subject = $event->getObject();
        if (!$subject instanceof CourseInterface) {
            return;
        }

        $context = $event->getContext();
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $visitor->setData('transitions', $context->accept($this->workflow->getEnabledTransitions($subject)));
    }

    public function onSerializeAppendTrainer(ObjectEvent $event): void
    {
        $subject = $event->getObject();
        if (!$subject instanceof CourseInterface || !$subject->getTrainerId()) {
            return;
        }

        $context = $event->getContext();
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $trainer = $this->commandBus->handle(new FindTrainerQuery($subject->getTrainerId(), $subject->getLocale()));
        $visitor->setData('trainer', $context->accept($trainer));
    }
}
