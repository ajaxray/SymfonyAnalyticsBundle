<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Driver;


use Symfony\Component\HttpFoundation\Request;

abstract class AbstractDriver {

	/**
	 * AbstractDriver constructor.
	 *
	 * @param $persistenceManager mixed Connection from DBAL, PRedis or something else
	 * @param $prefix string Prefix for collection names
	 */
	abstract function __construct($persistenceManager, $prefix);

	abstract public function isPrepared();
	abstract public function prepare();
	abstract public function clearData();
	abstract public function clearAll();
	abstract public function saveRequest(Request $request);
}