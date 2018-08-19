<?php

namespace Sprungbrett\Component\Content\Tests\Unit\Model;

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
        $contentable->getStructureType()->willReturn('default');
        $contentable->getContentData()->willReturn(['title' => 'Sprungbrett is great']);

        $propertyResolver->resolveContentData('default', 'title', 'Sprungbrett is great')
            ->willReturn('Sprungbrett is awesome')
            ->shouldBeCalled();

        $propertyResolver->resolveViewData('default', 'title', 'Sprungbrett is great')
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
}
