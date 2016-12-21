<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\EventListener;


use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequestEventListener {

	/**
	 * Check if the listener was fired
	 * @var bool
	 */
	static public $handled = false;

	/**
	 * Persistence driver.
	 * @var mixed
	 */
	protected $driver;

	abstract protected function processRequest(Request $request);
}