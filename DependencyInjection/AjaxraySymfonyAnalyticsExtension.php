<?php

namespace Ajaxray\SymfonyAnalyticsBundle\DependencyInjection;

use Ajaxray\SymfonyAnalyticsBundle\EventListener\AutoRequestEventListener;
use Ajaxray\SymfonyAnalyticsBundle\EventListener\ManualRequestEventListener;
use Ajaxray\SymfonyAnalyticsBundle\SymfonyAnalyticsBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AjaxraySymfonyAnalyticsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);
		//print_r($config); die;

        $container->setParameter('tracking_method', $config['tracking_method']);
        $this->_registerServiceFor($config['tracking_method'], $container);

        if(count($config['exclude_patterns']) && count($config['only_patterns'])) {
            throw new \LogicException('Only one of analytics.exclude_patterns or analytics.only_patterns can be set. But found both!', SymfonyAnalyticsBundle::MUTUALLY_EXCLUSIVE_CONFIG);
        }

        if(isset($config['exclude_patterns'])) {
	        $container->setParameter('exclude_patterns', $config['exclude_patterns']);
        }
        if(isset($config['only_patterns'])) {
	        $container->setParameter('only_patterns', $config['only_patterns']);
        }
	    if(isset($config['watch_groups'])) {
		    $container->setParameter('watch_groups', $config['watch_groups']);
	    }


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'analytics';
    }

	private function _registerServiceFor($tracking_method, ContainerBuilder $container) {
		switch ( $tracking_method ) {
			case 'auto':
				$service = new Definition(AutoRequestEventListener::class);
				$service->addTag('kernel.event_listener', ['event' => 'kernel.controller', 'method' => 'onKernelController']);
				$container->setDefinition('ajaxray.symfony_analytics.auto_request_listener', $service);
				break;
			case 'manual':
				$service = new Definition(ManualRequestEventListener::class);
				$service->addTag('kernel.event_listener', ['event' => 'analytics.request.manual', 'method' => 'onRequest']);
				$container->setDefinition('ajaxray.symfony_analytics.manual_request_listener', $service);
				break;
			//    default:
			//        code to be executed if n is different from all labels;
		}
	}
}
