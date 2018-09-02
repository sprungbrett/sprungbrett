<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Attendee;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

interface CourseAttendeeInterface
{
    const WORKFLOW_STAGE_NEW = 'new';
    const WORKFLOW_STAGE_INTERESTED = 'interested';

    const TRANSITION_SHOW_INTEREST = 'show_interest';

    public function __construct(AttendeeInterface $attendee, CourseInterface $course);

    public function getAttendee(): AttendeeInterface;

    public function getCourse(): CourseInterface;

    public function getWorkflowStage(): string;

    public function setWorkflowStage(string $workflowStage): CourseAttendeeInterface;
}
