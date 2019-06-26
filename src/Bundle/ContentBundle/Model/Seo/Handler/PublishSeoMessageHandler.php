<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Seo\Event\SeoPublishedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Exception\SeoNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Message\PublishSeoMessage;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class PublishSeoMessageHandler
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

    public function __invoke(PublishSeoMessage $message): void
    {
        $draftSeo = $this->seoRepository->findByResource(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::DRAFT,
            $message->getLocale()
        );
        if (!$draftSeo) {
            throw new SeoNotFoundException($message->getResourceKey(), $message->getResourceId());
        }

        $liveSeo = $this->seoRepository->findByResource(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::LIVE,
            $message->getLocale()
        );
        if (!$liveSeo) {
            $liveSeo = $this->seoRepository->create(
                $message->getResourceKey(),
                $message->getResourceId(),
                Stages::LIVE,
                $message->getLocale()
            );
        }

        $liveSeo->setTitle($draftSeo->getTitle());
        // TODO other fields

        $this->messageCollector->push(new SeoPublishedEvent($liveSeo));
    }
}
