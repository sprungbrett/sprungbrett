<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sprungbrett\Component\Course\Model\CourseInterface;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Symfony\Component\Workflow\Workflow;

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
        $this->assertEquals('Sprungbrett', $result['_embedded']['courses'][0]['title']);
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
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }

    public function testPost()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/courses?locale=en',
            [
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_TEST, $result['workflowStage']);
        $this->assertArrayNotHasKey('route', $result);
    }

    public function testPostPublish()
    {
        $client = $this->createAuthenticatedClient();
        $client->request(
            'POST',
            '/api/courses?locale=en&action=publish',
            [
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $result);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals('/sprungbrett', $result['route']);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $result['workflowStage']);
    }

    public function testPut()
    {
        $course = $this->createCourse('Sulu', 'Sprungbrett is great');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/courses/' . $course->getId() . '?locale=en',
            [
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getId(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_TEST, $result['workflowStage']);
        $this->assertArrayNotHasKey('route', $result);
    }

    public function testPutPublish()
    {
        $course = $this->createCourse('Sulu', 'Sprungbrett is great');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/courses/' . $course->getId() . '?locale=en&action=publish',
            [
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getId(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals('/sprungbrett', $result['route']);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_PUBLISHED, $result['workflowStage']);
    }

    public function testPutUnpublish()
    {
        $course = $this->createCourse('Sulu', 'Sprungbrett is great');
        $this->publish($course);

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/courses/' . $course->getId() . '?locale=en&action=unpublish',
            [
                'title' => 'Sprungbrett',
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($course->getId(), $result['id']);
        $this->assertEquals('Sprungbrett', $result['title']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
        $this->assertEquals(CourseInterface::WORKFLOW_STAGE_TEST, $result['workflowStage']);
        $this->assertArrayNotHasKey('route', $result);
    }

    public function testDelete()
    {
        $course = $this->createCourse();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'DELETE',
            '/api/courses/' . $course->getId() . '?locale=en'
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function getWorkflow(): Workflow
    {
        return $this->getContainer()->get('workflow.course');
    }
}
