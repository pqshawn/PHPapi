<?php
namespace PhpApi\Model;

use PhpApi\Standard\Model\ModelInterface;
use PhpApi\Standard\Factory\FactoryInterface;

use PhpApi\Di;

/**
 * db factory
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

abstract class ModelFactory implements FactoryInterface, ModelInterface {
	private $_db_driver = '';
	public $_db_prefix = '';
	public $_db = '';
	public $alias = '';
	public function __construct($confDb = array()) {
		$this->load($confDb);
	}

	public function load($confDb = array()) {
		if (empty($confDb)) return null;
		if (isset($confDb['type'])) {
			$this->_db_driver = $dbDriver = $confDb['type'];
			$db_driver = ucfirst($dbDriver);

			$alias = isset($confDb['alias'])? $confDb['alias'] : $dbDriver;

			// check 某引擎（可能有同引擎不同库的情况，对象也是不同的）对象已经建立，则不需要再重新加载
			$this->alias = $alias;
			// 一定要赋值到类属性_db
			if (is_object($this->_db = Di::single()->$alias)) {
				return true;
			}

			Di::single()->$alias = "\\PhpApi\\Dao\\{$db_driver}";
		} else {
			$this->_db_driver = 'mysqli';
			Di::single()->mysqli = "\\PhpApi\\Dao\\Mysqli";
		}
		if(isset($confDb['prefix'])) {
			$this->_db_prefix = $confDb['prefix'];
		} else {
			$this->_db_prefix = '';
		}
		$this->_db = $this->router();
		$this->_db->setConfig($confDb);
	}

	public function __destruct() {

	}

	public function router() {
		$alias = empty($this->alias)? $this->_db_driver : $this->alias;
		return Di::single()->$alias;
	}

	public function create($sql) {
		$res = $this->_db->exec($sql);
		return $res;
	}

	public function update($sql) {}
	public function retrieve($sql) {
		$data = $this->_db->select($sql);
		return $data;
	}
	public function delete($sql) {}

	public function table($tablename = null) {
		if(!empty($tablename)) {
			$showtab = "SHOW TABLES LIKE '%$tablename%'";
			$result = $this->_db->select($showtab);
			return $result;
		} else {
			return false;
		}
	}

}