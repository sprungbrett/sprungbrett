<?php

namespace Sprungbrett\Component\Course\Model\Exception;

use Sprungbrett\Component\Course\Model\Course;

/**
 * TODO extract ModelNotFoundException.
 */
class CourseNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id, $code = 0, \Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Model "%s" with id "%s" not found', Course::class, $id),
            $code,
            $previous
        );

        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
