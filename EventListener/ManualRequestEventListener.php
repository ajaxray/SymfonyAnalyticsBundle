<?php
namespace Ajaxray\SymfonyAnalyticsBundle\EventListener;

use Ajaxray\SymfonyAnalyticsBundle\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
class ManualRequestEventListener extends AbstractRequestEventListener {

	function __construct($driver = null) {
		$this->driver = $driver;
	}

	public function onRequest(RequestEvent $event)
	{
		$this->processRequest($event->getRequest());
	}

	protected function processRequest(Request $request) {
		$this->driver->saveRequest($request);
		self::$handled = true;
	}
}