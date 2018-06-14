<?php

namespace Sprungbrett\Component\Course\Model\Exception;

use Sprungbrett\Component\Course\Model\Course;
use Sprungbrett\Component\Uuid\Model\Uuid;

/**
 * TODO extract ModelNotFoundException.
 */
class CourseNotFoundException extends \Exception
{
    /**
     * @var Uuid
     */
    private $uuid;

    public function __construct(Uuid $uuid, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model "%s" with id "%s" not found', Course::class, $uuid->getId()),
            $code,
            $previous
        );

        $this->uuid = $uuid;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getId(): string
    {
        return $this->uuid->getId();
    }
}
