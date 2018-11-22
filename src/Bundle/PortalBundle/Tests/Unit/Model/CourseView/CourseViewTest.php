<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseView;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sulu\Bundle\RouteBundle\Model\RouteInterface;

class CourseViewTest extends TestCase
{
    /**
     * @var CourseViewInterface
     */
    private $courseView;

    protected function setUp()
    {
        $this->courseView = new CourseView('123-123-123', 'en');
    }

    public function testGetUuid(): void
    {
        $this->assertEquals('123-123-123', $this->courseView->getUuid());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->courseView->getLocale());
    }

    public function testContent(): void
    {
        $content = $this->prophesize(ContentInterface::class);

        $this->assertEquals($this->courseView, $this->courseView->setContent($content->reveal()));
        $this->assertEquals($content->reveal(), $this->courseView->getContent());
    }

    public function testCourse(): void
    {
        $course = $this->prophesize(CourseInterface::class);

        $this->assertEquals($this->courseView, $this->courseView->setCourse($course->reveal()));
        $this->assertEquals($course->reveal(), $this->courseView->getCourse());
    }

    public function testRoute(): void
    {
        $route = $this->prophesize(RouteInterface::class);

        $this->assertEquals($this->courseView, $this->courseView->setRoute($route->reveal()));
        $this->assertEquals($route->reveal(), $this->courseView->getRoute());
    }
}
