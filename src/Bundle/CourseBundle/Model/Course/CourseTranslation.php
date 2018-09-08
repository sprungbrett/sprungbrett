<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course;

use Sprungbrett\Component\Content\Model\ContentableTrait;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationTrait;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

class CourseTranslation implements CourseTranslationInterface, RoutableInterface, AuditableInterface
{
    use AuditableTrait;
    use ContentableTrait;
    use TranslationTrait;

    /**
     * @var CourseInterface
     */
    protected $course;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var RouteInterface|null
     */
    protected $route;

    public function __construct(CourseInterface $course, Localization $localization, ContentInterface $content)
    {
        $this->initializeTranslation($localization);
        $this->initializeContent($content);

        $this->course = $course;
    }

    public function getCourse(): CourseInterface
    {
        return $this->course;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): CourseTranslationInterface
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): CourseTranslationInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function removeRoute(): CourseTranslationInterface
    {
        $this->route = null;

        return $this;
    }

    public function getRoutePath(): ?string
    {
        if (!$this->route) {
            return null;
        }

        return $this->route->getPath();
    }

    public function getId()
    {
        return $this->course->getId();
    }
}
