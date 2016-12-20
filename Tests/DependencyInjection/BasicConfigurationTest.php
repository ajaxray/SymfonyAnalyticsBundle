<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\DependencyInjection;

use Ajaxray\SymfonyAnalyticsBundle\Tests\BundleTestCase;

class BasicConfigurationTest extends BundleTestCase {

	public function testItLoadsTrackingMethod()
	{
		$this->bootKernelWithEnv('test_manual_tracking');
        $this->assertEquals('manual', $this->container->getParameter('tracking_method'));
	}

	public function testItLoadsExcludePatterns()
	{
		$this->bootKernelWithEnv('test');
		$patterns = $this->container->getParameter('exclude_patterns');

		$this->assertTrue(is_array($patterns));
		$this->assertEquals(2, count($patterns));
	}

	public function testItLoadsOnlyPatterns()
	{
		$this->bootKernelWithEnv('test_include_patterns');
		$patterns = $this->container->getParameter('only_patterns');

		$this->assertTrue(is_array($patterns));
		$this->assertEquals(3, count($patterns));
	}

	public function testItLoadsWatchGroupsAs2dArray()
	{
		$this->bootKernelWithEnv('test');
		$patterns = $this->container->getParameter('watch_groups');

		$this->assertTrue(is_array($patterns));
		$this->assertEquals(2, count($patterns));
		$this->assertEquals('Products', $patterns[0]['name']);
		$this->assertEquals(2, count($patterns[1]['patterns']));
	}

	/**
	 * @expectedException     \LogicException
	 * @expectedExceptionCode \Ajaxray\SymfonyAnalyticsBundle\SymfonyAnalyticsBundle::MUTUALLY_EXCLUSIVE_CONFIG
	 */
	public function testExcludeAndIncludePatternsAreMutuallyExclusive()
	{
		$this->bootKernelWithEnv('test_exclude_include');
	}
}
