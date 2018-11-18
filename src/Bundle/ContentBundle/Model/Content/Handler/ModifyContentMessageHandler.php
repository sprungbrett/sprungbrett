<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentModifiedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Exception\ContentNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Message\ModifyContentMessage;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifyContentMessageHandler
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

    public function __invoke(ModifyContentMessage $message)
    {
        $content = $this->contentRepository->findByResource(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::DRAFT,
            $message->getLocale()
        );

        if (!$content) {
            throw new ContentNotFoundException($message->getResourceKey(), $message->getResourceId());
        }

        $content->modifyData($message->getType(), $message->getData());

        $this->messageCollector->push(new ContentModifiedEvent($content));
    }
}
