<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\ScheduleTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class ScheduleControllerTest extends SuluTestCase
{
    use CourseTrait;
    use ScheduleTrait;

    protected function setUp()
    {
        $this->purgeDatabase();
    }

    public function testCGet()
    {
        $course = $this->createCourse('Course Sprungbrett');
        $schedule = $this->createSchedule($course);

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/schedules?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, $result['total']);
        $this->assertCount(1, $result['_embedded']['schedules']);
        $this->assertEquals($schedule->getUuid(), $result['_embedded']['schedules'][0]['id']);
        $this->assertEquals('Sprungbrett', $result['_embedded']['schedules'][0]['name']);
        $this->assertEquals('Course Sprungbrett', $result['_embedded']['schedules'][0]['course']);
    }

    public function testPost()
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/schedules?locale=en',
            [
                'name' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
                'course' => $course->getUuid(),
                'minimumParticipants' => 5,
                'maximumParticipants' => 15,
                'price' => 15.5,
            ]
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sprungbrett', $result['name']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals($course->getUuid(), $result['course']);
        $this->assertEquals(5, $result['minimumParticipants']);
        $this->assertEquals(15, $result['maximumParticipants']);
        $this->assertEquals(15.5, $result['price']);

        $schedule = $this->findSchedule($result['id'], 'en', Stages::LIVE);
        $this->assertNull($schedule);
    }

    public function testPostAndPublish()
    {
        $course = $this->createCourse();
        $this->publishCourse($course);

        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/schedules?locale=en&action=publish',
            [
                'name' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
                'course' => $course->getUuid(),
                'minimumParticipants' => 5,
                'maximumParticipants' => 15,
                'price' => 15.5,
            ]
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sprungbrett', $result['name']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals($course->getUuid(), $result['course']);
        $this->assertEquals(5, $result['minimumParticipants']);
        $this->assertEquals(15, $result['maximumParticipants']);
        $this->assertEquals(15.5, $result['price']);

        $schedule = $this->findSchedule($result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($schedule);
    }

    public function testGet()
    {
        $course = $this->createCourse();
        $schedule = $this->createSchedule($course);

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/schedules/' . $schedule->getUuid() . '?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($schedule->getUuid(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['name']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals($course->getUuid(), $result['course']);
    }

    public function testPut()
    {
        $course = $this->createCourse();
        $schedule = $this->createSchedule($course, 'Sulu');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/schedules/' . $schedule->getUuid() . '?locale=en',
            [
                'name' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
                'course' => $course->getUuid(),
                'minimumParticipants' => 5,
                'maximumParticipants' => 15,
                'price' => 15.5,
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($schedule->getUuid(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['name']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals($course->getUuid(), $result['course']);
        $this->assertEquals(5, $result['minimumParticipants']);
        $this->assertEquals(15, $result['maximumParticipants']);
        $this->assertEquals(15.5, $result['price']);

        $schedule = $this->findSchedule($result['id'], 'en', Stages::LIVE);
        $this->assertNull($schedule);
    }

    public function testPutAndPublish()
    {
        $course = $this->createCourse();
        $this->publishCourse($course);
        $schedule = $this->createSchedule($course, 'Sulu');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/schedules/' . $schedule->getUuid() . '?locale=en&action=publish',
            [
                'name' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
                'course' => $course->getUuid(),
                'minimumParticipants' => 5,
                'maximumParticipants' => 15,
                'price' => 15.5,
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($schedule->getUuid(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['name']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals($course->getUuid(), $result['course']);
        $this->assertEquals(5, $result['minimumParticipants']);
        $this->assertEquals(15, $result['maximumParticipants']);
        $this->assertEquals(15.5, $result['price']);

        $schedule = $this->findSchedule($result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($schedule);
    }

    public function testDelete()
    {
        $course = $this->createCourse();
        $schedule = $this->createSchedule($course);
        $uuid = $schedule->getUuid();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'DELETE',
            '/api/schedules/' . $uuid . '?locale=en'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());

        $schedule = $this->findSchedule($uuid);
        $this->assertNull($schedule);
    }

    public function getMessageBus(): MessageBusInterface
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get('message_bus');

        return $messageBus;
    }
}
