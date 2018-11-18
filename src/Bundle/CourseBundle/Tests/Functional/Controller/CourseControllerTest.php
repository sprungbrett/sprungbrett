<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\ContentBundle\Tests\Functional\Traits\ContentTrait;
use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class CourseControllerTest extends SuluTestCase
{
    use CourseTrait;
    use ContentTrait;

    protected function setUp()
    {
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
        $this->assertEquals($course->getUuid(), $result['_embedded']['courses'][0]['id']);
        $this->assertEquals('Sprungbrett', $result['_embedded']['courses'][0]['name']);
    }

    public function testPost()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/courses?locale=en',
            [
                'name' => 'Sprungbrett',
            ]
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sprungbrett', $result['name']);

        $course = $this->findCourse($result['id'], 'en', Stages::LIVE);
        $this->assertNull($course);

        $content = $this->findContent('courses', $result['id'], 'en', Stages::LIVE);
        $this->assertNull($content);
    }

    public function testPostAndPublish()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/courses?locale=en&action=publish',
            [
                'name' => 'Sprungbrett',
            ]
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sprungbrett', $result['name']);

        $course = $this->findCourse($result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($course);

        $content = $this->findContent('courses', $result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($content);
    }

    public function testGet()
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/courses/' . $course->getUuid() . '?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getUuid(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['name']);
    }

    public function testPut()
    {
        $course = $this->createCourse('Sulu');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/courses/' . $course->getUuid() . '?locale=en',
            [
                'name' => 'Sprungbrett',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getUuid(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['name']);

        $course = $this->findCourse($result['id'], 'en', Stages::LIVE);
        $this->assertNull($course);

        $content = $this->findContent('courses', $result['id'], 'en', Stages::LIVE);
        $this->assertNull($content);
    }

    public function testPutAndPublish()
    {
        $course = $this->createCourse('Sulu');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/courses/' . $course->getUuid() . '?locale=en&action=publish',
            [
                'name' => 'Sprungbrett',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getUuid(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['name']);

        $course = $this->findCourse($result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($course);

        $content = $this->findContent('courses', $result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($content);
    }

    public function testDelete()
    {
        $course = $this->createCourse();
        $uuid = $course->getUuid();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'DELETE',
            '/api/courses/' . $uuid . '?locale=en'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $course = $this->findCourse($uuid);
        $this->assertNull($course);

        $content = $this->findContent('courses', $uuid);
        $this->assertNull($content);
    }

    public function getMessageBus(): MessageBusInterface
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get('message_bus');

        return $messageBus;
    }
}
