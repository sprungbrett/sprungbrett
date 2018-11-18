<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Seo\Event\SeoRemovedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Message\RemoveSeoMessage;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoRepositoryInterface;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class RemoveSeoMessageHandler
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

    public function __invoke(RemoveSeoMessage $message): void
    {
        $seos = $this->seoRepository->findAllByResource($message->getResourceKey(), $message->getResourceId());
        foreach ($seos as $seo) {
            $this->seoRepository->remove($seo);
            $this->messageCollector->push(new SeoRemovedEvent($seo));
        }
    }
}
