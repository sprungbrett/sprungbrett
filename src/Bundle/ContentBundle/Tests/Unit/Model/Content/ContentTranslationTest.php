<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentTranslation;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentTranslationInterface;

class ContentTranslationTest extends TestCase
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var ContentTranslationInterface
     */
    private $translation;

    protected function setUp(): void
    {
        $this->content = $this->prophesize(ContentInterface::class);
        $this->translation = new ContentTranslation($this->content->reveal(), 'en');
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->translation->getLocale());
    }

    public function testGetType(): void
    {
        $this->assertNull($this->translation->getType());
    }

    public function testGetData(): void
    {
        $this->assertEquals([], $this->translation->getData());
    }

    public function testModifyData(): void
    {
        $this->assertEquals($this->translation, $this->translation->modifyData('default', ['title' => 'Sprungbrett']));
        $this->assertEquals('default', $this->translation->getType());
        $this->assertEquals(['title' => 'Sprungbrett'], $this->translation->getData());
    }
}
