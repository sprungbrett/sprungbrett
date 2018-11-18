<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentCreatedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\CreateContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class CreateContentMessageHandler
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

    public function __invoke(CreateContentMessage $message)
    {
        $content = $this->contentRepository->create(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::DRAFT,
            $message->getLocale()
        );

        $content->modifyData($message->getType(), $message->getData());

        $this->messageCollector->push(new ContentCreatedEvent($content));
    }
}
