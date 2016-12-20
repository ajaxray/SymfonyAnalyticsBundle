<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
namespace Ajaxray\SymfonyAnalyticsBundle\Tests;

use Ajaxray\SymfonyAnalyticsBundle\Tests\app\AppKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BundleTestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ContainerInterface
	 */
	protected $container;

	protected function bootKernelWithEnv($env = 'test', $debug = true)
	{
		$kernel = new AppKernel($env, $debug);
		$kernel->boot();

		$this->container = $kernel->getContainer();
	}

}
