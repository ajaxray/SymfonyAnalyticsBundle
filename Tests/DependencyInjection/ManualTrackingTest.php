<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\DependencyInjection;

use Ajaxray\SymfonyAnalyticsBundle\Event\RequestEvent;
use Ajaxray\SymfonyAnalyticsBundle\EventListener\ManualRequestEventListener;
use Ajaxray\SymfonyAnalyticsBundle\Tests\BundleTestCase;
use Symfony\Component\HttpFoundation\Request;

class ManualTrackingTest extends BundleTestCase {

	protected function setUp() {
		parent::setUp();

		ManualRequestEventListener::$handled = false;
		$this->bootKernelWithEnv('test_manual_tracking');
	}

	public function testItLoadsTrackingMethod()
	{
        $this->assertEquals('manual', $this->container->getParameter('analytics.tracking_method'));
	}

	public function testManualTrackingServiceRegistered()
	{
		$service = $this->container->get('symfony_analytics.manual_request_listener');

		$this->assertNotNull($service);
		$this->assertTrue(is_a($service, ManualRequestEventListener::class));
	}

	public function testManualTrackingCanBeFiredManually()
	{
		$this->container->get('symfony_analytics.persistence')->prepare();

		$this->assertFalse(ManualRequestEventListener::$handled, 'Wasn\'t Handled dispatching event');
		$requestEvent = new RequestEvent(Request::create('/'));
		$this->container->get('event_dispatcher')
		                ->dispatch('analytics.request.manual', $requestEvent);
		$this->assertTrue(ManualRequestEventListener::$handled, 'Handled with manual dispatch');
	}

	public function testManualTrackingServiceCalledOnRequest()
	{
		$this->container->get('symfony_analytics.persistence')->prepare();
		$client = $this->container->get('test.client');

		$this->assertFalse(ManualRequestEventListener::$handled, 'Wasn\'t Handled before request');
		$client->request('GET', '/');
		$this->assertFalse(ManualRequestEventListener::$handled, 'Wasn\'t Handled if controller don\'t dispatch event manually');
		$client->request('GET', '/manual');
		$this->assertTrue(ManualRequestEventListener::$handled, 'Handled during request (with manual dispatch) processing');

		// Clear logged data
		$this->container->get('symfony_analytics.persistence')->clearData();
	}

}
