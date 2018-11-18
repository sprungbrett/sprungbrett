<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Unit\Model\Content\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Query\FindContentQuery;
use Sprungbrett\Bundle\ContentBundle\Stages;

class FindContentQueryTest extends TestCase
{
    /**
     * @var FindContentQuery
     */
    private $query;

    protected function setUp()
    {
        $this->query = new FindContentQuery('courses', '123-123-123', 'en', Stages::LIVE);
    }

    public function testGetResourceKey(): void
    {
        $this->assertEquals('courses', $this->query->getResourceKey());
    }

    public function testGetResourceId(): void
    {
        $this->assertEquals('123-123-123', $this->query->getResourceId());
    }

    public function testGetLocale(): void
    {
        $this->assertEquals('en', $this->query->getLocale());
    }

    public function testGetStage(): void
    {
        $this->assertEquals(Stages::LIVE, $this->query->getStage());
    }
}
