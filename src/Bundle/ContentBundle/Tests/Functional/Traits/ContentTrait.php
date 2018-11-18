<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\ContentBundle\Tests\Functional\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Sprungbrett\Bundle\ContentBundle\Model\Content\Content;
use Sprungbrett\Bundle\ContentBundle\Model\Content\ContentInterface;
use Sprungbrett\Bundle\ContentBundle\Stages;

trait ContentTrait
{
    public function createContent(
        string $resourceKey,
        string $resourceId,
        array $data = [],
        string $type = 'default',
        string $locale = 'en'
    ): ContentInterface {
        $content = new Content($resourceKey, $resourceId, Stages::DRAFT);
        $content->setCurrentLocale($locale);
        $content->modifyData($type, $data);

        $this->getEntityManager()->persist($content);
        $this->getEntityManager()->flush();

        return $content;
    }

    public function findContent(
        string $resourceKey,
        string $resourceId,
        ?string $locale = null,
        string $stage = Stages::DRAFT
    ): ?ContentInterface {
        $repository = $this->getEntityManager()->getRepository(Content::class);

        /** @var ContentInterface|null $content */
        $content = $repository->findOneBy(
            ['resourceKey' => $resourceKey, 'resourceId' => $resourceId, 'stage' => $stage]
        );
        if ($content && $locale) {
            $content->setCurrentLocale($locale);
        }

        return $content;
    }

    /**
     * @return EntityManagerInterface
     */
    abstract public function getEntityManager();
}
