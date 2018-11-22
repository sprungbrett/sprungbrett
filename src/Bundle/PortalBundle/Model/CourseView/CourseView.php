<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Model\CourseView;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;

class CourseView implements CourseViewInterface
{
    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var CourseInterface
     */
    protected $course;

    /**
     * @var ContentInterface
     */
    protected $content;

    /**
     * @var RouteInterface
     */
    protected $route;

    public function __construct(string $uuid, string $locale)
    {
        $this->uuid = $uuid;
        $this->locale = $locale;
    }

    public function getId()
    {
        return $this->uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getCourse(): CourseInterface
    {
        $this->course->setCurrentLocale($this->locale);

        return $this->course;
    }

    public function setCourse(CourseInterface $course): CourseViewInterface
    {
        $this->course = $course;

        return $this;
    }

    public function getContent(): ContentInterface
    {
        $this->content->setCurrentLocale($this->locale);

        return $this->content;
    }

    public function setContent(ContentInterface $content): CourseViewInterface
    {
        $this->content = $content;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route)
    {
        $this->route = $route;

        return $this;
    }
}
