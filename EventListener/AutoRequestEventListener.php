<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\EventListener;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AutoRequestEventListener extends AbstractRequestEventListener {

	function __construct($driver) {
		$this->driver = $driver;
	}

	public function onKernelController(FilterControllerEvent $event)
	{
		// $controller = $event->getController();
		$request = $event->getRequest();

		if(HttpKernelInterface::MASTER_REQUEST == $event->getRequestType() && !self::$handled ) {
			$this->processRequest($request);
		}
	}

	protected function processRequest(Request $request)
	{
		$this->driver->saveRequest($request);
		self::$handled = true;
	}
}