<?php

namespace Sprungbrett\Bundle\CourseBundle\Model\Handler;

use Sprungbrett\Bundle\CourseBundle\Model\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Query\ListCoursesQuery;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilder;
use Sulu\Component\Rest\ListBuilder\Doctrine\DoctrineListBuilderFactoryInterface;
use Sulu\Component\Rest\ListBuilder\FieldDescriptor;
use Sulu\Component\Rest\ListBuilder\ListRepresentation;
use Sulu\Component\Rest\ListBuilder\Metadata\FieldDescriptorFactoryInterface;
use Sulu\Component\Rest\RestHelperInterface;

class ListCoursesQueryHandler
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

    public function __invoke(ListCoursesQuery $command): ListRepresentation
    {
        $fieldDescriptors = $this->getFieldDescriptors(Course::class, $command->getLocale());

        /** @var DoctrineListBuilder $listBuilder */
        $listBuilder = $this->listBuilderFactory->create(Course::class);
        $this->restHelper->initializeListBuilder($listBuilder, $fieldDescriptors);
        $listBuilder->setIdField($fieldDescriptors['id']);

        $listResponse = $listBuilder->execute();

        return new ListRepresentation(
            $listResponse,
            'courses',
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
