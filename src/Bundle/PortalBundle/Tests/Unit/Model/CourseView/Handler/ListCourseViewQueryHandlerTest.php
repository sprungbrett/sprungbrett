<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler\ListCourseViewQueryHandler;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\ListCourseViewQuery;

class ListCourseViewQueryHandlerTest extends TestCase
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    /**
     * @var ListCourseViewQueryHandler
     */
    private $handler;

    /**
     * @var CourseViewInterface
     */
    private $courseView;

    /**
     * @var ListCourseViewQuery
     */
    private $event;

    protected function setUp()
    {
        $this->courseViewRepository = $this->prophesize(CourseViewRepositoryInterface::class);

        $this->handler = new ListCourseViewQueryHandler($this->courseViewRepository->reveal());

        $this->courseView = $this->prophesize(CourseViewInterface::class);

        $this->event = $this->prophesize(ListCourseViewQuery::class);
        $this->event->getLocale()->willReturn('en');
        $this->event->getPage()->willReturn(1);
        $this->event->getPageSize()->willReturn(10);
    }

    public function testInvoke(): void
    {
        $this->courseViewRepository->list('en', 1, 10)->willReturn([$this->courseView->reveal()]);

        $result = $this->handler->__invoke($this->event->reveal());
        $this->assertEquals([$this->courseView->reveal()], $result);
    }
}
