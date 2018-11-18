<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\ContentBundle\Tests\Functional\Traits\ContentTrait;
use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class CourseContentControllerTest extends SuluTestCase
{
    use CourseTrait;
    use ContentTrait;

    protected function setUp()
    {
        $this->purgeDatabase();
    }

    public function testCGet(): void
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/course-contents?locale=en'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testGet(): void
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/course-contents/' . $course->getUuid() . '?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('default', $result['template']);
        $this->assertEquals('', $result['title']);
        $this->assertEquals('', $result['description']);
    }

    public function testPut(): void
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/course-contents/' . $course->getUuid() . '?locale=en',
            [
                'template' => 'default',
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('default', $result['template']);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }

    public function testPutAndPublish(): void
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/course-contents/' . $course->getUuid() . '?locale=en&action=publish',
            [
                'template' => 'default',
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals('default', $result['template']);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);

        $course = $this->findCourse($result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($course);

        $content = $this->findContent('courses', $result['id'], 'en', Stages::LIVE);
        $this->assertNotNull($content);
    }

    public function getMessageBus(): MessageBusInterface
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get('message_bus');

        return $messageBus;
    }
}
