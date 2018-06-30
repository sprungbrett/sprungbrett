<?php

namespace Sprungbrett\Component\Content\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Content\Model\ContentInterface;
use Sprungbrett\Component\Content\Tests\Asset\ContentableAsset;

class ContentableTraitTest extends TestCase
{
    public function testSetStructureType()
    {
        $content = $this->prophesize(ContentInterface::class);
        $contentable = new ContentableAsset($content->reveal());

        $content->setStructureType('default')->shouldBeCalled();

        $this->assertEquals($contentable, $contentable->setStructureType('default'));
    }

    public function testSetContentData()
    {
        $content = $this->prophesize(ContentInterface::class);
        $contentable = new ContentableAsset($content->reveal());

        $content->setContentData(['title' => 'Sprungbrett is awesome'])->shouldBeCalled();

        $this->assertEquals($contentable, $contentable->setContentData(['title' => 'Sprungbrett is awesome']));
    }

    public function testGetStructureType()
    {
        $content = $this->prophesize(ContentInterface::class);
        $contentable = new ContentableAsset($content->reveal());

        $content->getStructureType()->shouldBeCalled()->willReturn('default');

        $this->assertEquals('default', $contentable->getStructureType());
    }

    public function testGetContentData()
    {
        $content = $this->prophesize(ContentInterface::class);
        $contentable = new ContentableAsset($content->reveal());

        $content->getContentData()->shouldBeCalled()->willReturn(['title' => 'Sprungbrett is awesome']);

        $this->assertEquals(['title' => 'Sprungbrett is awesome'], $contentable->getContentData());
    }
}
