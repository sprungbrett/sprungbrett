<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Course\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Query\ListCourseQuery;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilder;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\FieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\RestHelperInterface;

class ListCourseQueryHandler
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

    public function handle(ListCourseQuery $command): ListRepresentation
    {
        $fieldDescriptors = $this->getFieldDescriptors($command->getEntityClass(), $command->getLocale());

        /** @var DoctrineListBuilder $listBuilder */
        $listBuilder = $this->listBuilderFactory->create($command->getEntityClass());
        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);
        $listBuilder->setIdField($fieldDescriptors['id']);

        // FIXME when column-options are implemented
        $listBuilder->setSelectFields($fieldDescriptors);

        $listResponse = $listBuilder->execute();

        return new ListRepresentation(
            $listResponse,
            $command->getResourceKey(),
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
