<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Event;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class RequestEvent extends Event {
	/**
	 * @var Request
	 */
	private $request;

	function __construct(Request $request) {
		$this->request = $request;
	}

	/**
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}

}