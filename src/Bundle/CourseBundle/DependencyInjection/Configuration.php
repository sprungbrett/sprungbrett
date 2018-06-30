<?php

namespace Sprungbrett\Bundle\CourseBundle\DependencyInjection;

use Sprungbrett\Bundle\CourseBundle\Model\Course\Course;
use Sprungbrett\Bundle\CourseBundle\Model\Course\CourseTranslation;
use Sprungbrett\Bundle\CourseBundle\Repository\CourseRepository;
use Sprungbrett\Bundle\CourseBundle\Repository\CourseTranslationRepository;
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
        $rootNode = $treeBuilder->root('sprungbrett_course');

        $this->addObjectsSection($rootNode);

        return $treeBuilder;
    }

    private function addObjectsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('objects')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('course')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue(Course::class)->end()
                                ->scalarNode('repository')->defaultValue(CourseRepository::class)->end()
                            ->end()
                        ->end()
                        ->arrayNode('course-translation')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->defaultValue(CourseTranslation::class)->end()
                                ->scalarNode('repository')->defaultValue(CourseTranslationRepository::class)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
