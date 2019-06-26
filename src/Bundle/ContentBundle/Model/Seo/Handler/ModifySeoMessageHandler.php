<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Seo\Event\SeoModifiedEvent;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Exception\SeoNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Message\ModifySeoMessage;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Component\MessageCollector\MessageCollector;

class ModifySeoMessageHandler
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

    public function __invoke(ModifySeoMessage $message)
    {
        $seo = $this->seoRepository->findByResource(
            $message->getResourceKey(),
            $message->getResourceId(),
            Stages::DRAFT,
            $message->getLocale()
        );

        if (!$seo) {
            throw new SeoNotFoundException($message->getResourceKey(), $message->getResourceId());
        }

        $seo->setTitle($message->getTitle());
        // TODO other fields

        $this->messageCollector->push(new SeoModifiedEvent($seo));
    }
}
