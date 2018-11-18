<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentPublishedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Exception\ContentNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\PublishContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class PublishContentMessageHandler
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var ContentRepositoryInterface
     */
    private $contentRepository;

    public function __construct(MessageCollector $messageCollector, ContentRepositoryInterface $contentRepository)
    {
        $this->messageCollector = $messageCollector;
        $this->contentRepository = $contentRepository;
    }

    public function __invoke(PublishContentMessage $message): void
    {
        $draftContent = $this->contentRepository->findByResource(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::DRAFT,
            $message->getLocale()
        );
        if (!$draftContent) {
            throw new ContentNotFoundException($message->getResourceKey(), $message->getResourceId());
        }

        $liveContent = $this->contentRepository->findByResource(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::LIVE,
            $message->getLocale()
        );
        if (!$liveContent) {
            $liveContent = $this->contentRepository->create(
                $message->getResourceKey(),
                $message->getResourceId(),
                Stages::LIVE,
                $message->getLocale()
            );
        }

        $liveContent->modifyData($draftContent->getType(), $draftContent->getData());

        $this->messageCollector->push(new ContentPublishedEvent($liveContent));
    }
}
