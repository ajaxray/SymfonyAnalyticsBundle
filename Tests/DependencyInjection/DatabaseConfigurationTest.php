<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\DependencyInjection;

use Ajaxray\SymfonyAnalyticsBundle\Tests\BundleTestCase;

class DatabaseConfigurationTest extends BundleTestCase {

	public function testItLoadsDefaultDriverIfNotSetConfig()
	{
		$this->bootKernelWithEnv('test');
		$driver = $this->container->getParameter('analytics.driver');

		$this->assertTrue(is_array($driver));
		$this->assertEquals('dbal', $driver['type']);
		$this->assertEquals('default_connection', $driver['connection']);
		$this->assertEquals('analytics', $driver['prefix']);
	}

	public function testDriverInfoCanBeChanged()
	{
		$this->bootKernelWithEnv('test_redis_driver');
		$driver = $this->container->getParameter('analytics.driver');

		$this->assertTrue(is_array($driver));
		$this->assertEquals('redis', $driver['type']);
		$this->assertEquals('default', $driver['connection']);
		$this->assertEquals('visitor_tracking', $driver['prefix']);
	}

	public function testPersistenceServiceRegistered()
	{
		$this->bootKernelWithEnv('test');
		$driver = $this->container->get('symfony_analytics.persistence');

		$this->assertEquals('Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal\DbalDriver', get_class($driver));
	}

}
