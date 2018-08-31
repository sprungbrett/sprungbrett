<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\AttendeeTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class AttendeeControllerTest extends SuluTestCase
{
    use AttendeeTrait;

    public function setUp()
    {
        parent::setUp();

        $this->purgeDatabase();
    }

    public function testGet()
    {
        $attendee = $this->createAttendee();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/attendees/' . $attendee->getId() . '?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($attendee->getId(), $result['id']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }

    public function testPut()
    {
        $attendee = $this->createAttendee('Sprungbrett is great');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/attendees/' . $attendee->getId() . '?locale=en',
            [
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($attendee->getId(), $result['id']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }

    public function testPutCreate()
    {
        $attendee = $this->createAttendee();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/attendees/' . $attendee->getId() . '?locale=en',
            [
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($attendee->getId(), $result['id']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }
}
