<?php

namespace Sprungbrett\Bundle\CourseBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\TrainerTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;

class TrainerControllerTest extends SuluTestCase
{
    use TrainerTrait;

    public function setUp()
    {
        parent::setUp();

        $this->purgeDatabase();
    }

    public function testGet()
    {
        $trainer = $this->createTrainer();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'GET',
            '/api/trainers/' . $trainer->getId() . '?locale=en'
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($trainer->getId(), $result['id']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }

    public function testPut()
    {
        $trainer = $this->createTrainer('Sprungbrett is great');

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/trainers/' . $trainer->getId() . '?locale=en',
            [
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($trainer->getId(), $result['id']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }

    public function testPutCreate()
    {
        $trainer = $this->createTrainer();

        $client = $this->createAuthenticatedClient();
        $client->request(
            'PUT',
            '/api/trainers/' . $trainer->getId() . '?locale=en',
            [
                'description' => 'Sprungbrett is awesome',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $result = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($trainer->getId(), $result['id']);
        $this->assertEquals('Sprungbrett is awesome', $result['description']);
    }
}
