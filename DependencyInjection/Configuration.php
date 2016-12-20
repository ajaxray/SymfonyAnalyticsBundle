<?php

namespace Ajaxray\SymfonyAnalyticsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('analytics');

        $rootNode
            ->children()
                ->scalarNode('tracking_method')
                    ->defaultValue("auto")
                ->end()
                ->arrayNode('exclude_patterns')
	                ->prototype('scalar')->end()
                ->end()
                ->arrayNode('only_patterns')
	                ->prototype('scalar')->end()
                ->end()
	            ->arrayNode('watch_groups')
	                ->normalizeKeys(false)
	                ->prototype('array')
	                ->children()
	                    ->scalarNode('name')->cannotBeEmpty()->end()
	                    ->arrayNode('patterns')->prototype('scalar')->end()
	                ->end()
	            ->end()
            ->end();

        return $treeBuilder;
    }
}
