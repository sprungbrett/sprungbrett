<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Schedule\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Exception\ScheduleNotFoundException;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler\FindScheduleQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\FindScheduleQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\ScheduleRepositoryInterface;

class FindScheduleQueryHandlerTest extends TestCase
{
    /**
     * @var ScheduleRepositoryInterface
     */
    private $repository;

    /**
     * @var FindScheduleQueryHandler
     */
    private $handler;

    /**
     * @var FindScheduleQuery
     */
    private $query;

    /**
     * @var ScheduleInterface
     */
    private $schedule;

    protected function setUp()
    {
        $this->repository = $this->prophesize(ScheduleRepositoryInterface::class);

        $this->handler = new FindScheduleQueryHandler($this->repository->reveal());

        $this->query = $this->prophesize(FindScheduleQuery::class);
        $this->query->getUuid()->willReturn('123-123-123');
        $this->query->getLocale()->willReturn('en');
        $this->query->getStage()->willReturn(Stages::LIVE);

        $this->schedule = $this->prophesize(ScheduleInterface::class);
    }

    public function testInvoke(): void
    {
        $this->repository->findById('123-123-123', Stages::LIVE, 'en')->willReturn($this->schedule->reveal());

        $result = $this->handler->__invoke($this->query->reveal());
        $this->assertEquals($this->schedule->reveal(), $result);
    }

    public function testInvokeNotFound(): void
    {
        $this->expectException(ScheduleNotFoundException::class);

        $this->repository->findById('123-123-123', Stages::LIVE, 'en')->willReturn(null);

        $this->handler->__invoke($this->query->reveal());
    }
}
