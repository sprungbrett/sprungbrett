<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class CourseControllerTest extends SuluTestCase
{
    use CourseTrait;

    public function setUp()
    {
        parent::setUp();

        $this->purgeDatabase();
    }

    public function testCGet()
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/courses?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, $result['total']);
        $this->assertCount(1, $result['_embedded']['courses']);
        $this->assertEquals($course->getId(), $result['_embedded']['courses'][0]['id']);
        $this->assertEquals('Sulu is awesome', $result['_embedded']['courses'][0]['title']);
    }

    public function testGet()
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/courses/' . $course->getId() . '?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getId(), $result['id']);
        $this->assertEquals('Sulu is awesome', $result['title']);
    }

    public function testPost()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/courses?locale=en',
            [
                'title' => 'Sulu is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sulu is awesome', $result['title']);
    }

    public function testPut()
    {
        $course = $this->createCourse('Sulu is great');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/courses/' . $course->getId() . '?locale=en',
            [
                'title' => 'Sulu is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getId(), $result['id']);
        $this->assertEquals('Sulu is awesome', $result['title']);
    }

    public function testDelete()
    {
        $course = $this->createCourse('Sulu is great');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'DELETE',
            '/api/courses/' . $course->getId() . '?locale=en'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
