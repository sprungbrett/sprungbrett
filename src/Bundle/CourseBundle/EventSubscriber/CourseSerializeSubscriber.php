<?php

namespace Sprungbrett\Bundle\CourseBundle\EventSubscriber;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\JsonSerializationVisitor;
use Sprungbrett\Bundle\CourseBundle\Entity\Course;
use Symfony\Component\Workflow\Workflow;

class CourseSerializeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => Events::POST_SERIALIZE,
                'method' => 'onSerialize',
                'class' => Course::class,
                'format' => 'json',
            ],
        ];
    }

    /**
     * @var Workflow
     */
    private $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    public function onSerialize(ObjectEvent $event): void
    {
        $subject = $event->getObject();
        if (!$subject instanceof Course) {
            return;
        }

        $context = $event->getContext();
        /** @var JsonSerializationVisitor $visitor */
        $visitor = $event->getVisitor();

        $visitor->setData('transitions', $context->accept($this->workflow->getEnabledTransitions($subject)));
    }
}
