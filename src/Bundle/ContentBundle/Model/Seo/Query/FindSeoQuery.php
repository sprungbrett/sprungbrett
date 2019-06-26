<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Model\Seo\Query;

use Sprungbrett\Bundle\ContentBundle\Stages;

class FindSeoQuery
{
    /**
     * @var string
     */
    private $resourceKey;

    /**
     * @var string
     */
    private $resourceId;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var string
     */
    private $stage;

    public function __construct(string $resourceKey, string $resourceId, string $locale, string $stage = Stages::DRAFT)
    {
        $this->resourceKey = $resourceKey;
        $this->resourceId = $resourceId;
        $this->locale = $locale;
        $this->stage = $stage;
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getStage(): string
    {
        return $this->stage;
    }
}
