<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal;


use Ajaxray\SymfonyAnalyticsBundle\Driver\AbstractDriver;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Request;

class RedisDriver extends AbstractDriver {

	/**
	 * @var Connection
	 */
	protected $connection;

	/**
	 * Prefix for storage collection name
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * DBALDriver constructor.
	 *
	 * @param $connection string connection name. Default "default"
	 * @param string $prefix
	 */
	public function __construct($connection, $prefix) {
		$this->connection = $connection;
	}

	public function isPrepared() {
		// TODO: Implement isPrepared() method.
	}

	public function prepare() {
		// TODO: Implement prepare() method.
	}

	public function saveRequest(Request $request) {
		// TODO: Implement saveRequest() method.
	}

	public function clearData() {
		// TODO: Implement clearData() method.
	}

	public function clearAll() {
		// TODO: Implement clearAll() method.
	}
}