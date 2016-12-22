<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Tests\Driver\Dbal;

use Ajaxray\SymfonyAnalyticsBundle\Driver\Dbal\DbalDriver;
use Ajaxray\SymfonyAnalyticsBundle\Tests\BundleTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test besic operations of DBAL Driver
 *
 * @package Ajaxray\SymfonyAnalyticsBundle
 */
class DbalDriverTest extends BundleTestCase {

	/**
	 * DBAL Driver
	 *
	 * @var DbalDriver
	 */
	private $driver;

	protected function setUp() {
		parent::setUp();

		$this->bootKernelWithEnv('test_dbal_driver');
		$this->driver = $this->container->get('symfony_analytics.persistence');
	}

	protected function tearDown() {
		parent::tearDown();
		$this->driver->clearAll();
	}

	public function testPrepareCreatesTablesWithPrefix()
	{
		$driverConf = $this->container->getParameter('analytics.driver');
		$connection = $this->container->get('doctrine.dbal.'. $driverConf['connection']. '_connection');
		$sm = $connection->getSchemaManager();

		$this->driver->clearAll();
		$tablesWithPrefix = $this->getPrefixedTables($sm, $driverConf);
		$this->assertEmpty($tablesWithPrefix);

		$this->driver->prepare();
		$tablesWithPrefix = $this->getPrefixedTables($sm, $driverConf);
		$this->assertNotEmpty($tablesWithPrefix);
	}

	public function testIsPrepareCanCheckIfTablesArePrepared()
	{
		$this->driver->clearAll();
		$this->assertFalse($this->driver->isPrepared());
		$this->driver->prepare();
		$this->assertTrue($this->driver->isPrepared());
	}

	public function testClearDataRemovesDataFromAnalyticsTables()
	{
		$driverConf = $this->container->getParameter('analytics.driver');
		$connection = $this->container->get('doctrine.dbal.'. $driverConf['connection']. '_connection');

		$tableName = $driverConf['prefix']. 'requests';
		$data = ['happened' => time(), 'ip' => '127.0.0.1', 'url' => 'http://localhost/', 'method' => 'GET', 'path' => '/'];

		$this->driver->prepare();
		$connection->insert($tableName, $data);
		$connection->insert($tableName, $data);

		$rowsBeforeClear = $connection->fetchAll("SELECT * FROM $tableName");
		$this->driver->clearData();
		$rowsAfterClear = $connection->fetchAll("SELECT * FROM $tableName");

		$this->assertEquals(2, count($rowsBeforeClear));
		$this->assertEquals(0, count($rowsAfterClear));
	}

	public function testClearAllRemovesOnlyPreparedTables()
	{
		$driverConf = $this->container->getParameter('analytics.driver');
		$connection = $this->container->get('doctrine.dbal.'. $driverConf['connection']. '_connection');
		$sm = $connection->getSchemaManager();

		$this->haveSomeOtherTable($connection);
		$tablesBefore = $this->getNames($sm->listTables());
		$tablesBeforeWithPrefix = $this->getNames($this->getPrefixedTables($sm, $driverConf));

		$this->driver->prepare();
		$tablesAfterPrepare = $this->getNames($sm->listTables());
		$tablesAfterPrepareWithPrefix = $this->getNames($this->getPrefixedTables($sm, $driverConf));

		$this->driver->clearAll();
		$tablesAfterClearAll = $this->getNames($sm->listTables());
		$tablesAfterClearAllWithPrefix = $this->getNames($this->getPrefixedTables($sm, $driverConf));

		$this->assertEquals($tablesBefore, $tablesAfterClearAll);
		$this->assertEquals($tablesBeforeWithPrefix, $tablesAfterClearAllWithPrefix);
		$this->assertGreaterThan(count($tablesBefore), count($tablesAfterPrepare));
		$this->assertGreaterThan(count($tablesBeforeWithPrefix), count($tablesAfterPrepareWithPrefix));
	}

	/**
	 * @param $sm
	 * @param $driverConf
	 *
	 * @return array
	 */
	private function getPrefixedTables( $sm, $driverConf ) {
		$tablesWithPrefix = array_filter($sm->listTables(), function ( $table ) use ( $driverConf ) {
			return strstr($table->getName(), $driverConf['prefix'] );
		});

		return $tablesWithPrefix;
	}

	private function getNames($tables)
	{
		return array_map(function($table) {
			return $table->getName();
		}, $tables);
	}

	/**
	 * @param $connection
	 */
	private function haveSomeOtherTable( $connection )
	{
		$connection->beginTransaction();
		$connection->query('CREATE TABLE IF NOT EXISTS not_analytics_table_1(`id` INTEGER)');
		$connection->query('CREATE TABLE IF NOT EXISTS not_analytics_table_2(`id` INTEGER)');
		$connection->commit();
	}

}