<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Component\Messenger\MessageBusInterface;

class CourseControllerTest extends SuluTestCase
{
    use CourseTrait;

    protected function setUp()
    {
        $this->purgeDatabase();
        $this->initPhpcr();
    }

    public function testIndexAction(): void
    {
        $course = $this->createCourse();
        $this->publishCourse($course);

        $client = $this->createClient(['sulu_context' => SuluKernel::CONTEXT_WEBSITE]);
        $client->request('GET', '/en/courses/sprungbrett');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains('<h1>Sprungbrett is awesome</h1>', $client->getResponse()->getContent());
    }

    public function testIndexActionNotPublished(): void
    {
        $this->createCourse();

        $client = $this->createClient(['sulu_context' => SuluKernel::CONTEXT_WEBSITE]);
        $client->request('GET', '/en/courses/sprungbrett');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function getMessageBus(): MessageBusInterface
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get('message_bus');

        return $messageBus;
    }
}
