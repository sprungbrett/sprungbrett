<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Handler;

use Sprungbrett\Bundle\ContentBundle\Model\Seo\Exception\SeoNotFoundException;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\Query\FindSeoQuery;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoRepositoryInterface;

class FindSeoQueryHandler
{
    /**
     * @var SeoRepositoryInterface
     */
    private $seoRepository;

    public function __construct(SeoRepositoryInterface $seoRepository)
    {
        $this->seoRepository = $seoRepository;
    }

    public function __invoke(FindSeoQuery $query): SeoInterface
    {
        $seo = $this->seoRepository->findByResource(
            $query->getResourceKey(),
            $query->getResourceId(),
            $query->getStage(),
            $query->getLocale()
        );

        if (!$seo) {
            throw new SeoNotFoundException($query->getResourceKey(), $query->getResourceId());
        }

        return $seo;
    }
}
