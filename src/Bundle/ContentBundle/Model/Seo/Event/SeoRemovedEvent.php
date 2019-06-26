<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Event;

use Sprungbrett\Bundle\ContentBundle\Model\Seo\SeoInterface;

class SeoRemovedEvent
{
    /**
     * @var SeoInterface
     */
    private $seo;

    public function __construct(SeoInterface $seo)
    {
        $this->seo = $seo;
    }

    public function getSeo(): SeoInterface
    {
        return $this->seo;
    }
}
