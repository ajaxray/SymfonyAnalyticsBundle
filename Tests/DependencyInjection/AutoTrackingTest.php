<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\DependencyInjection;

use Ajaxray\SymfonyAnalyticsBundle\EventListener\AutoRequestEventListener;
use Ajaxray\SymfonyAnalyticsBundle\Tests\BundleTestCase;

class AutoTrackingTest extends BundleTestCase {

	protected function setUp() {
		parent::setUp();

		AutoRequestEventListener::$handled = false;
	}

	public function testDefaultTrackingMethodIsAuto()
	{
		$this->bootKernelWithEnv('test');
        $this->assertEquals('auto', $this->container->getParameter('analytics.tracking_method'));
	}

	public function testAutoTrackingServiceRegistered()
	{
		$this->bootKernelWithEnv('test');
		$service = $this->container->get('symfony_analytics.auto_request_listener');

		$this->assertNotNull($service);
		$this->assertTrue(is_a($service, AutoRequestEventListener::class));
	}

	public function testAutoTrackingServiceCalledOnRequest()
	{
		$this->bootKernelWithEnv('test');
		$client = $this->container->get('test.client');
		$this->container->get('symfony_analytics.persistence')->prepare();

		$this->assertFalse(AutoRequestEventListener::$handled, 'Wasn\'t Handled before request');
		$client->request('GET', '/');
		$this->assertTrue(AutoRequestEventListener::$handled, 'Handled during request processing');

		// Clear logged data
		$this->container->get('symfony_analytics.persistence')->clearData();
	}

}
