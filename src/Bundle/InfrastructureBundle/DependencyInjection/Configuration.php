<?php

namespace Sprungbrett\Bundle\InfrastructureBundle\DependencyInjection;

use Sprungbrett\Bundle\CourseBundle\Model\Attendee\Attendee;
use Sprungbrett\Bundle\CourseBundle\Model\Attendee\AttendeeTranslation;
use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\Trainer;
use Sprungbrett\Bundle\CourseBundle\Model\Trainer\TrainerTranslation;
use Sprungbrett\Bundle\CourseBundle\Repository\AttendeeRepository;
use Sprungbrett\Bundle\CourseBundle\Repository\AttendeeTranslationRepository;
use Sprungbrett\Bundle\CourseBundle\Repository\CourseRepository;
use Sprungbrett\Bundle\CourseBundle\Repository\CourseTranslationRepository;
use Sprungbrett\Bundle\CourseBundle\Repository\TrainerRepository;
use Sprungbrett\Bundle\CourseBundle\Repository\TrainerTranslationRepository;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sprungbrett_infrastructure');

        $rootNode->children()
                ->arrayNode('resources')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('datagrid')->isRequired()->end()
                            ->arrayNode('workflow_stages')
                                ->prototype('scalar')->end()
                            ->end()
                            ->scalarNode('endpoint')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
