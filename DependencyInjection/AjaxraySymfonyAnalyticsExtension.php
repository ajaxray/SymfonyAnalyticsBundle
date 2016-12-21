<?php

namespace Ajaxray\SymfonyAnalyticsBundle\DependencyInjection;

use Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal\DbalDriver;
use Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal\RedisDriver;
use Ajaxray\SymfonyAnalyticsBundle\EventListener\AutoRequestEventListener;
use Ajaxray\SymfonyAnalyticsBundle\EventListener\ManualRequestEventListener;
use Ajaxray\SymfonyAnalyticsBundle\SymfonyAnalyticsBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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
		// print_r($config); die;

        $container->setParameter('analytics.tracking_method', $config['tracking_method']);
        $container->setParameter('analytics.driver', $config['driver']);

        $this->_registerPersistenceService($config['driver'], $container);
	    $this->_registerTrackingService($config['tracking_method'], $container);

        if(count($config['exclude_patterns']) && count($config['only_patterns'])) {
            throw new \LogicException('Only one of analytics.exclude_patterns or analytics.only_patterns can be set. But found both!', SymfonyAnalyticsBundle::MUTUALLY_EXCLUSIVE_CONFIG);
        }

        if(isset($config['exclude_patterns'])) {
	        $container->setParameter('analytics.exclude_patterns', $config['exclude_patterns']);
        }
        if(isset($config['only_patterns'])) {
	        $container->setParameter('analytics.only_patterns', $config['only_patterns']);
        }
	    if(isset($config['watch_groups'])) {
		    $container->setParameter('analytics.watch_groups', $config['watch_groups']);
	    }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'analytics';
    }

	private function _registerPersistenceService($driver, ContainerBuilder $container) {
		switch ($driver['type']) {
			case 'dbal':
				$driverService = new Reference('doctrine.dbal.'. $driver['connection']);
				$service = new Definition(DbalDriver::class, [$driverService , $driver['prefix']]);
				$container->setDefinition('symfony_analytics.persistence', $service);
				break;
			case 'redis':
				$service = new Definition(RedisDriver::class, ['not-done-yet' , $driver['prefix']]);
				$container->setDefinition('symfony_analytics.persistence', $service);
				break;
		}
	}

	private function _registerTrackingService($tracking_method, ContainerBuilder $container) {
    	$persistenceDriver = new Reference('symfony_analytics.persistence');
		switch ( $tracking_method ) {
			case 'auto':
				$service = new Definition(AutoRequestEventListener::class, [$persistenceDriver]);
				$service->addTag('kernel.event_listener', ['event' => 'kernel.controller', 'method' => 'onKernelController']);
				$container->setDefinition('symfony_analytics.auto_request_listener', $service);
				break;
			case 'manual':
				$service = new Definition(ManualRequestEventListener::class, [$persistenceDriver]);
				$service->addTag('kernel.event_listener', ['event' => 'analytics.request.manual', 'method' => 'onRequest']);
				$container->setDefinition('symfony_analytics.manual_request_listener', $service);
				break;
		}
	}
}
