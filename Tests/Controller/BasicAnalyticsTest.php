<?php
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\Controller;

use Ajaxray\SymfonyAnalyticsBundle\Tests\app\AppKernel;
use Ajaxray\SymfonyAnalyticsBundle\Tests\BundleTestCase;
use Symfony\Component\BrowserKit\Client;

/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
class BasicAnalyticsTest extends BundleTestCase {
	/**
	 * @var Client
	 */
	private $client;

	protected function setUp()
	{
		$this->bootKernelWithEnv('test');
		$this->client = $this->container->get('test.client');
	}

	public function testItRunsSuccessfully()
	{
		$response = $this->client->request('GET', '/');
        $this->assertContains('Welcome to the native Analytics of Symfony!', $response->html());
     }
}
