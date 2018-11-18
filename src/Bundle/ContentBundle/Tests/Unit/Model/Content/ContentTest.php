<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Content;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentTranslationInterface;
use Sprungbrett\Bundle\ContentBundle\Stages;

class ContentTest extends TestCase
{
    /**
     * @var ContentInterface
     */
    private $content;

    /**
     * @var ContentTranslationInterface
     */
    private $translation;

    protected function setUp()
    {
        $this->translation = $this->prophesize(ContentTranslationInterface::class);

        $this->content = new Content('courses', '123-123-123', Stages::LIVE, ['en' => $this->translation->reveal()]);
    }

    public function testGetResourceKey(): void
    {
        $this->assertEquals('courses', $this->content->getResourceKey());
    }

    public function testGetResourceId(): void
    {
        $this->assertEquals('123-123-123', $this->content->getResourceId());
    }

    public function testGetStage(): void
    {
        $this->assertEquals(Stages::LIVE, $this->content->getStage());
    }

    public function testGetType(): void
    {
        $this->translation->getType()->willReturn('default');
        $this->content->setCurrentLocale('en');

        $this->assertEquals('default', $this->content->getType());
    }

    public function testGetTypeWithLocale(): void
    {
        $this->translation->getType()->willReturn('default');

        $this->assertEquals('default', $this->content->getType('en'));
    }

    public function testGetData(): void
    {
        $this->translation->getData()->willReturn(['title' => 'Sprungbrett']);
        $this->content->setCurrentLocale('en');

        $this->assertEquals(['title' => 'Sprungbrett'], $this->content->getData());
    }

    public function testGetDataWithLocale(): void
    {
        $this->translation->getData()->willReturn(['title' => 'Sprungbrett']);

        $this->assertEquals(['title' => 'Sprungbrett'], $this->content->getData('en'));
    }

    public function testModifyData(): void
    {
        $this->translation->modifyData('default', ['title' => 'Sprungbrett'])->shouldBeCalled();
        $this->content->setCurrentLocale('en');

        $this->assertEquals($this->content, $this->content->modifyData('default', ['title' => 'Sprungbrett']));
    }

    public function testModifyDataWithLocale(): void
    {
        $this->translation->modifyData('default', ['title' => 'Sprungbrett'])->shouldBeCalled();

        $this->assertEquals($this->content, $this->content->modifyData('default', ['title' => 'Sprungbrett'], 'en'));
    }

    public function testGetTranslation(): void
    {
        $this->content->setCurrentLocale('en');

        $this->assertEquals($this->translation->reveal(), $this->content->getTranslation());
    }

    public function testGetTranslationWithLocale(): void
    {
        $this->assertEquals($this->translation->reveal(), $this->content->getTranslation('en'));
    }

    public function testGetTranslationNew(): void
    {
        $translation = $this->content->getTranslation('de');

        $this->assertInstanceOf(ContentTranslationInterface::class, $translation);
        $this->assertEquals('de', $translation->getLocale());
    }
}
