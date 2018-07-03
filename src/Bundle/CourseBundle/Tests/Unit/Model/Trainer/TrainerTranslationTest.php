<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Trainer;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerInterface;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerTranslation;
use Sprungbrett\Component\Translation\Model\Localization;

class TrainerTranslationTest extends TestCase
{
    public function testGetTrainer()
    {
        $localization = $this->prophesize(Localization::class);
        $trainer = $this->prophesize(TrainerInterface::class);

        $translation = new TrainerTranslation($trainer->reveal(), $localization->reveal());

        $this->assertEquals($trainer->reveal(), $translation->getTrainer());
    }

    public function testGetLocalization()
    {
        $localization = $this->prophesize(Localization::class);
        $trainer = $this->prophesize(TrainerInterface::class);

        $translation = new TrainerTranslation($trainer->reveal(), $localization->reveal());

        $this->assertEquals($localization->reveal(), $translation->getLocalization());
    }

    public function testGetDescription()
    {
        $localization = $this->prophesize(Localization::class);
        $trainer = $this->prophesize(TrainerInterface::class);

        $translation = new TrainerTranslation($trainer->reveal(), $localization->reveal());

        $translation->setDescription('Sprungbrett is awesome');

        $this->assertEquals('Sprungbrett is awesome', $translation->getDescription());
    }
}
