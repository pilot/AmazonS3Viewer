<?php

namespace AmazonS3\ViewBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('view');

        $rootNode
            ->children()
                ->arrayNode('client_data')
                    ->isRequired()
                    ->children()
                        ->scalarNode('key')->isRequired()->end()
                        ->scalarNode('secret')->isRequired()->end()
                        ->scalarNode('region')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
