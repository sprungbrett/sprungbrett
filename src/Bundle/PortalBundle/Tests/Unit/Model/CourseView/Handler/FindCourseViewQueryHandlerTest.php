<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Unit\Model\CourseView\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\CourseViewRepositoryInterface;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Exception\CourseViewNotFoundException;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Handler\FindCourseViewQueryHandler;
use Sprungbrett\Bundle\PortalBundle\Model\CourseView\Query\FindCourseViewQuery;

class FindCourseViewQueryHandlerTest extends TestCase
{
    /**
     * @var CourseViewRepositoryInterface
     */
    private $courseViewRepository;

    /**
     * @var FindCourseViewQueryHandler
     */
    private $handler;

    /**
     * @var CourseViewInterface
     */
    private $courseView;

    /**
     * @var FindCourseViewQuery
     */
    private $event;

    protected function setUp()
    {
        $this->courseViewRepository = $this->prophesize(CourseViewRepositoryInterface::class);

        $this->handler = new FindCourseViewQueryHandler($this->courseViewRepository->reveal());

        $this->courseView = $this->prophesize(CourseViewInterface::class);

        $this->event = $this->prophesize(FindCourseViewQuery::class);
        $this->event->getUuid()->willReturn('123-123-123');
        $this->event->getLocale()->willReturn('en');
    }

    public function testInvoke(): void
    {
        $this->courseViewRepository->findById('123-123-123', 'en')->willReturn($this->courseView->reveal());

        $result = $this->handler->__invoke($this->event->reveal());
        $this->assertEquals($this->courseView->reveal(), $result);
    }

    public function testInvokeNotFound(): void
    {
        $this->expectException(CourseViewNotFoundException::class);

        $this->courseViewRepository->findById('123-123-123', 'en')->willReturn(null);

        $this->handler->__invoke($this->event->reveal());
    }
}
