<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class CourseAttendee implements AuditableInterface, CourseAttendeeInterface
{
    use AuditableTrait;

    /**
     * @var AttendeeInterface
     */
    private $attendee;

    /**
     * @var CourseInterface
     */
    private $course;

    /**
     * @var string
     */
    protected $workflowStage = self::WORKFLOW_STAGE_NEW;

    public function __construct(AttendeeInterface $attendee, CourseInterface $course)
    {
        $this->attendee = $attendee;
        $this->course = $course;
    }

    public function getAttendee(): AttendeeInterface
    {
        return $this->attendee;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }

    public function getWorkflowStage(): string
    {
        return $this->workflowStage;
    }

    public function setWorkflowStage(string $workflowStage): CourseAttendeeInterface
    {
        $this->workflowStage = $workflowStage;

        return $this;
    }
}
