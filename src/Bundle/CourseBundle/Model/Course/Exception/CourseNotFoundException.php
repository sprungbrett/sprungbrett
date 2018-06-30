<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Exception;

use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;

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
            sprintf('Model "%s" with id "%s" not found', CourseInterface::class, $id),
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
