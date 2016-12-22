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

	/**
	 * Check if Driver has prepared collections
	 *
	 * @return boolean
	 */
	abstract public function isPrepared();

	/**
	 * Prepare storage collections and other preparations for storing data
	 *
	 * @return boolean
	 */
	abstract public function prepare();

	/**
	 * Clear analytics data (but don't remove collections)
	 *
	 * @return boolean
	 */
	abstract public function clearData();

	/**
	 * Clear analytics data and undo all preparations
	 *
	 * @return mixed
	 */
	abstract public function clearAll();

	/**
	 * Save a page hit
	 *
	 * @param Request $request
	 *
	 * @return integer
	 */
	abstract public function saveRequest(Request $request);
}