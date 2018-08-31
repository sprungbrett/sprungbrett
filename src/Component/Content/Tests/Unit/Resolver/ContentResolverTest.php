<?php

namespace Sprungbrett\Component\Content\Tests\Unit\Resolver;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Component\Content\Model\ContentableInterface;
use Sprungbrett\Component\Content\Resolver\ContentResolver;
use Sprungbrett\Component\Content\Resolver\PropertyResolverInterface;

class ContentResolverTest extends TestCase
{
    public function testResolve()
    {
        $propertyResolver = $this->prophesize(PropertyResolverInterface::class);
        $resolver = new ContentResolver($propertyResolver->reveal());

        $contentable = $this->prophesize(ContentableInterface::class);
        $contentable->getContentData()->willReturn(['title' => 'Sprungbrett is great']);

        $propertyResolver->getAvailableProperties($contentable->reveal())->willReturn(['title']);
        $propertyResolver->resolveContentData($contentable->reveal(), 'title', 'Sprungbrett is great')
            ->willReturn('Sprungbrett is awesome')
            ->shouldBeCalled();

        $propertyResolver->resolveViewData($contentable->reveal(), 'title', 'Sprungbrett is great')
            ->willReturn('view-data')
            ->shouldBeCalled();

        $this->assertEquals(
            [
                'content' => ['title' => 'Sprungbrett is awesome'],
                'view' => ['title' => 'view-data'],
            ],
            $resolver->resolve($contentable->reveal())
        );
    }

    public function testResolveNull()
    {
        $propertyResolver = $this->prophesize(PropertyResolverInterface::class);
        $resolver = new ContentResolver($propertyResolver->reveal());

        $contentable = $this->prophesize(ContentableInterface::class);
        $contentable->getContentData()->willReturn([]);

        $propertyResolver->getAvailableProperties($contentable->reveal())->willReturn(['title']);
        $propertyResolver->resolveContentData($contentable->reveal(), 'title', null)
            ->willReturn('')
            ->shouldBeCalled();

        $propertyResolver->resolveViewData($contentable->reveal(), 'title', null)
            ->willReturn([])
            ->shouldBeCalled();

        $this->assertEquals(
            [
                'content' => ['title' => ''],
                'view' => ['title' => []],
            ],
            $resolver->resolve($contentable->reveal())
        );
    }
}
