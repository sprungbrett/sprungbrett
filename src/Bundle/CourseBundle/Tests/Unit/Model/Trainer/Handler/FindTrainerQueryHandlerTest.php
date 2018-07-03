<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Trainer\Handler;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Handler\FindTrainerQueryHandler;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Query\FindTrainerQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerRepositoryInterface;
use Sprungbrett\Component\Translation\Model\Localization;

class FindTrainerQueryHandlerTest extends TestCase
{
    public function testHandle()
    {
        $repository = $this->prophesize(TrainerRepositoryInterface::class);
        $handler = new FindTrainerQueryHandler($repository->reveal());

        $localization = $this->prophesize(Localization::class);

        $trainer = $this->prophesize(TrainerInterface::class);
        $repository->findOrCreateTrainerById(42, $localization->reveal())->willReturn($trainer->reveal());

        $command = $this->prophesize(FindTrainerQuery::class);
        $command->getId()->willReturn(42);
        $command->getLocalization()->willReturn($localization->reveal());

        $result = $handler->handle($command->reveal());
        $this->assertEquals($trainer->reveal(), $result);
    }
}
