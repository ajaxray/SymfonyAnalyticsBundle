<?php
namespace Ajaxray\SymfonyAnalyticsBundle\EventListener;

use Ajaxray\SymfonyAnalyticsBundle\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
class ManualRequestEventListener extends AbstractRequestEventListener {

	public function onRequest(RequestEvent $event)
	{
		$this->processRequest($event->getRequest());
	}

	protected function processRequest(Request $request) {
		// @TODO : Save the request, no need to check filters
		self::$handled = true;
	}
}