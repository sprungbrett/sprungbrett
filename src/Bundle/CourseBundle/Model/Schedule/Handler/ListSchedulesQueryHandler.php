<?php

declare(strict_types=1);

namespace Sprungbrett\Bundle\CourseBundle\Model\Schedule\Handler;

use Sprungbrett\Bundle\ContentBundle\Stages;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Query\ListSchedulesQuery;
use Sprungbrett\Bundle\CourseBundle\Model\Schedule\Schedule;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilder;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\FieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\RestHelperInterface;

class ListSchedulesQueryHandler
{
    /**
     * @var DoctrineListBuilderFactoryInterface
     */
    private $listBuilderFactory;

    /**
     * @var RestHelperInterface
     */
    private $restHelper;

    /**
     * @var FieldDescriptorFactoryInterface
     */
    private $fieldDescriptorsFactory;

    public function __construct(
        DoctrineListBuilderFactoryInterface $listBuilderFactory,
        RestHelperInterface $restHelper,
        FieldDescriptorFactoryInterface $fieldDescriptorsFactory
    ) {
        $this->listBuilderFactory = $listBuilderFactory;
        $this->restHelper = $restHelper;
        $this->fieldDescriptorsFactory = $fieldDescriptorsFactory;
    }

    public function __invoke(ListSchedulesQuery $command): ListRepresentation
    {
        $fieldDescriptors = $this->getFieldDescriptors(Schedule::class, $command->getLocale());

        /** @var DoctrineListBuilder $listBuilder */
        $listBuilder = $this->listBuilderFactory->create(Schedule::class);
        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);
        $listBuilder->setIdField($fieldDescriptors['id']);

        $listBuilder->where($fieldDescriptors['stage'], Stages::DRAFT);
        $listBuilder->distinct();

        $listResponse = $listBuilder->execute();

        return new ListRepresentation(
            $listResponse,
            'schedules',
            $command->getRoute(),
            $command->getQuery(),
            $listBuilder->getCurrentPage(),
            $listBuilder->getLimit(),
            $listBuilder->count()
        );
    }

    /**
     * @return FieldDescriptor[]
     */
    protected function getFieldDescriptors(string $entityClass, ?string $locale): array
    {
        /** @var FieldDescriptor[] $fieldDescriptors */
        $fieldDescriptors = $this->fieldDescriptorsFactory->getFieldDescriptorForClass(
            $entityClass,
            ['locale' => $locale]
        );

        return $fieldDescriptors;
    }
}
