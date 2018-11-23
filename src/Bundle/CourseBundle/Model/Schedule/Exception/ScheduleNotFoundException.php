<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception;

use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;

/**
 * TODO extract ModelNotFoundException.
 */
class ScheduleNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model "%s" with id "%s" not found', ScheduleInterface::class, $uuid),
            $code,
            $previous
        );

        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
