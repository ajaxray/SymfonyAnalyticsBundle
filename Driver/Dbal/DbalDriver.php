<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal;


use Ajaxray\SymfonyAnalyticsBundle\Driver\AbstractDriver;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\HttpFoundation\Request;

/**
 * DBAL Driver for SymfonyAnalyticsBundle
 *
 *
 * @package Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal
 */
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
	 * @param $connection Connection from Doctrine DBAL.
	 * @param string $prefix
	 */
	public function __construct($connection, $prefix) {
		$this->connection = $connection;
		$this->prefix = $prefix;

		$this->tables['requests'] = $prefix . 'requests';
	}

	/**
	 * {@inheritDoc}
	 */
	public function isPrepared() {
		$sm = $this->connection->getSchemaManager();
		$allTables = array_map(function($table) {
			return $table->getName();
		}, $sm->listTables());

		return (0 === count(array_diff($this->tables, $allTables)));
	}

	/**
	 * {@inheritDoc}
	 */
	public function prepare() {
		$prepareSql = file_get_contents(__DIR__. '/data/prepare.sql');
		$prepareSql = str_replace('__PREFIX__', $this->prefix, $prepareSql);

		$this->connection->beginTransaction();
		try {
			$this->connection->query($prepareSql);
			$this->connection->commit();
		} catch (\Exception $e) {
			$this->connection->rollBack();
			throw new \RuntimeException('Preparing analytics table failed: '. $e->getMessage(), $e->getCode());
		}

//		$sm = $this->connection->getSchemaManager();
//		$tables = $sm->listTables();
//		foreach ($tables as $table) {
//			echo $table->getName() . " columns:\n\n";
//			foreach ($table->getColumns() as $column) {
//				echo ' - ' . $column->getName() . "\n";
//			}
//		}
//		die( 'Died in ' . __FILE__ . ' at line ' . __LINE__ );
	}

	/**
	 * {@inheritDoc}
	 */
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

	/**
	 * {@inheritDoc}
	 */
	public function clearData() {
		$this->connection->beginTransaction();
		foreach ($this->tables as $table) {
			$this->connection->query('DELETE FROM '. $table);
		}
		$this->connection->commit();
	}

	/**
	 * {@inheritDoc}
	 */
	public function clearAll()
	{
		$this->connection->beginTransaction();
		foreach ($this->tables as $table) {
			$this->connection->query('DROP TABLE IF EXISTS '. $table);
		}
		$this->connection->commit();
	}
}