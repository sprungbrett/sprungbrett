<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentRemovedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\RemoveContentMessage;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class RemoveContentMessageHandler
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

    public function __invoke(RemoveContentMessage $message): void
    {
        $contents = $this->contentRepository->findAllByResource($message->getResourceKey(), $message->getResourceId());
        foreach ($contents as $content) {
            $this->contentRepository->remove($content);
            $this->messageCollector->push(new ContentRemovedEvent($content));
        }
    }
}
