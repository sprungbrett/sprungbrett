<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Seo\Event\SeoCreatedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Message\CreateSeoMessage;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class CreateSeoMessageHandler
{
    /**
     * @var MessageCollector
     */
    private $messageCollector;

    /**
     * @var SeoRepositoryInterface
     */
    private $seoRepository;

    public function __construct(MessageCollector $messageCollector, SeoRepositoryInterface $seoRepository)
    {
        $this->messageCollector = $messageCollector;
        $this->seoRepository = $seoRepository;
    }

    public function __invoke(CreateSeoMessage $message)
    {
        $seo = $this->seoRepository->create(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::DRAFT,
            $message->getLocale()
        );

        $seo->setTitle($message->getTitle());
        // TODO other fields

        $this->messageCollector->push(new SeoCreatedEvent($seo));
    }
}
