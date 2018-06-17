<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Unit\Model\Query;

use PHPUnit\Framework\TestCase;
use Sprungbrett\Bundle\CourseBundle\Model\Query\ListCourseQuery;
use Sprungbrett\Component\Course\Model\CourseInterface;

class ListCourseQueryTest extends TestCase
{
    public function testGetEntityClass()
    {
        $command = new ListCourseQuery(CourseInterface::class, 'courses', 'de', 'sprungbrett.get_courses', []);

        $this->assertEquals(CourseInterface::class, $command->getEntityClass());
    }

    public function testGetResourceKey()
    {
        $command = new ListCourseQuery(CourseInterface::class, 'courses', 'de', 'sprungbrett.get_courses', []);

        $this->assertEquals('courses', $command->getResourceKey());
    }

    public function testGetLocale()
    {
        $command = new ListCourseQuery(CourseInterface::class, 'courses', 'de', 'sprungbrett.get_courses', []);

        $this->assertEquals('de', $command->getLocale());
    }

    public function testGetRoute()
    {
        $command = new ListCourseQuery(CourseInterface::class, 'courses', 'de', 'sprungbrett.get_courses', []);

        $this->assertEquals('sprungbrett.get_courses', $command->getRoute());
    }

    public function testGetQuery()
    {
        $query = ['page' => 1];
        $command = new ListCourseQuery(CourseInterface::class, 'courses', 'de', 'sprungbrett.get_courses', $query);

        $this->assertEquals($query, $command->getQuery());
    }
}
