<?php

namespace Sprungbrett\Bundle\CourseBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Sprungbrett\Component\Course\Model\Course as ComponentCourse;
use Sprungbrett\Component\Translation\Model\Localization;
use Sprungbrett\Component\Translation\Model\TranslationInterface;
use Sulu\Bundle\RouteBundle\Model\RoutableInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

/**
 * @Serializer\ExclusionPolicy("ALL")
 */
class Course extends ComponentCourse implements AuditableInterface, RoutableInterface
{
    use AuditableTrait;

    /**
     * @var RouteInterface|null
     */
    private $route;

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function removeRoute(): self
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

    protected function createTranslation(Localization $localization): TranslationInterface
    {
        return new CourseTranslation($this->getId(), $localization, $this);
    }
}
