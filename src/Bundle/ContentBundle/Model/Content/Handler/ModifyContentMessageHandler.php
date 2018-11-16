<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentCreatedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Event\ContentModifiedEvent;
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

        $modified = false;
        if (!$content) {
            $modified = true;
            $content = $this->contentRepository->create(
                $message->getResourceKey(),
                $message->getResourceId(),
                Stages::DRAFT,
                $message->getLocale()
            );
        }

        $content->modifyData($message->getType(), $message->getData());

        $message = new ContentModifiedEvent($content);
        if ($modified) {
            $message = new ContentCreatedEvent($content);
        }

        $this->messageCollector->push($message);
    }
}
