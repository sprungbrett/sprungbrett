<?php

namespace Sprungbrett\Component\Content\Tests\Unit\Model;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Content\Model\Content;

class ContentTest extends TestCase
{
    public function testGetStructureTypeNull()
    {
        $content = new Content();

        $this->assertEquals('', $content->getStructureType());
    }

    public function testGetContentDataNull()
    {
        $content = new Content();

        $this->assertEquals([], $content->getContentData());
    }

    public function testSetStructureType()
    {
        $content = new Content();

        $content->setStructureType('default');
        $this->assertEquals('default', $content->getStructureType());
    }

    public function testSetContentData()
    {
        $content = new Content();

        $content->setContentData(['title' => 'Sprungbrett is awesome']);
        $this->assertEquals(['title' => 'Sprungbrett is awesome'], $content->getContentData());
    }
}
