<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal;


use Ajaxray\SymfonyAnalyticsBundle\Driver\AbstractDriver;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Request;

class DbalDriver extends AbstractDriver {

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

	protected $tables = [];

	/**
	 * DBALDriver constructor.
	 *
	 * @param $connection Connection connection name. Default "default"
	 * @param string $prefix
	 */

	public function __construct($connection, $prefix) {
		$this->connection = $connection;

		$this->tables['requests'] = $prefix . '_requests';
	}

	public function isPrepared() {
		// TODO: Implement isPrepared() method.
	}

	public function prepare() {
		// TODO: Implement prepare() method.
	}

	public function saveRequest(Request $request) {

		$this->connection->insert($this->tables['requests'], [
			'happened' => time(),
			'ip' => $request->server->get('REMOTE_ADDR'),
			'url' => $request->getUri(),
			'method' => $request->getMethod(),
			'path' => $request->getRequestUri(),
			'route' => $request->attributes->get('_route'),
			'username' => $request->hasSession()? 'wait' : 'n/a',
			'session_key' => $request->hasSession()? 'wait' : 'n/a'
		]);
	}

	public function clearData() {
		$this->connection->beginTransaction();
		foreach ($this->tables as $table) {
			$this->connection->query('DELETE FROM '. $table);
		}
		$this->connection->commit();
	}

	public function clearAll()
	{
		$this->connection->beginTransaction();
		foreach ($this->tables as $table) {
			$this->connection->query('DROP TABLE IF EXISTS '. $table);
		}
		$this->connection->commit();
	}
}