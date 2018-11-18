<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\PortalBundle\Tests\Functional\Controller;

use Sprungbrett\Bundle\CourseBundle\Tests\Functional\Traits\CourseTrait;
use Sulu\Bundle\ContentBundle\Document\PageDocument;
use Sulu\Bundle\TestBundle\Testing\SuluTestCase;
use Sulu\Component\DocumentManager\DocumentManagerInterface;
use Sulu\Component\HttpKernel\SuluKernel;
use Symfony\Component\Messenger\MessageBusInterface;

class CourseListControllerTest extends SuluTestCase
{
    use CourseTrait;

    protected function setUp()
    {
        $this->purgeDatabase();
        $this->initPhpcr();
    }

    public function testIndexAction(): void
    {
        $this->createPage();
        $course = $this->createCourse();
        $this->publishCourse($course);

        $client = $this->createClient(['sulu_context' => SuluKernel::CONTEXT_WEBSITE]);
        $client->request('GET', '/en/courses');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertContains(
            '<a href="http://localhost/en/courses/sprungbrett">Sprungbrett</a>',
            $client->getResponse()->getContent()
        );
    }

    public function testIndexActionNotPublished(): void
    {
        $this->createPage();
        $this->createCourse();

        $client = $this->createClient(['sulu_context' => SuluKernel::CONTEXT_WEBSITE]);
        $client->request('GET', '/en/courses');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertNotContains('/courses/sprungbrett', $client->getResponse()->getContent());
    }

    public function getMessageBus(): MessageBusInterface
    {
        /** @var MessageBusInterface $messageBus */
        $messageBus = $this->getContainer()->get('message_bus');

        return $messageBus;
    }

    protected function createPage(): PageDocument
    {
        /** @var DocumentManagerInterface $documentManager */
        $documentManager = $this->getContainer()->get('sulu_document_manager.document_manager');

        /** @var PageDocument $document */
        $document = $documentManager->create('page');
        $document->setTitle('Sprungbrett');
        $document->setResourceSegment('/courses');
        $document->setStructureType('default');
        $document->getStructure()->bind(
            [
                'article' => '<p>Sprungbrett is awesome</p>',
            ]
        );
        $documentManager->persist($document, 'en', ['parent_path' => '/cmf/sprungbrett/contents']);
        $documentManager->publish($document, 'en');
        $documentManager->flush();

        return $document;
    }
}
