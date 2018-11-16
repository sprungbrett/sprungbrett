<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Content\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentRepositoryInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Exception\ContentNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Query\FindContentQuery;

class FindContentQueryHandler
{
    /**
     * @var ContentRepositoryInterface
     */
    private $contentRepository;

    public function __construct(ContentRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function __invoke(FindContentQuery $query): ContentInterface
    {
        $content = $this->contentRepository->findByResource(
            $query->getResourceKey(),
            $query->getResourceId(),
            $query->getStage(),
            $query->getLocale()
        );

        if (!$content) {
            throw new ContentNotFoundException($query->getResourceKey(), $query->getResourceId());
        }

        return $content;
    }
}
