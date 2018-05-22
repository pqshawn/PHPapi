<?php
/**
 * db factory
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Yzwu <Ldos.net>
 */

abstract class DbFactoryLib implements FactoryInterfaceLib, ModelInterfaceLib {
	private $_db_driver = '';
	public $_db_prefix = 'dos_';
	public $_db = '';
	public function __construct() {
		if(defined(DB_DRIVER)) {
			$this->_db_driver = DB_DRIVER;
		} else {
			$this->_db_driver = 'mysql';
		}
		if(defined(DB_PREFIX)) {
			$this->_db_prefix = DB_PREFIX;
		} else {
			$this->_db_prefix = 'dos_';
		}
		$this->_db = $this->router();
	}

	public function __destruct() {

	}

	public function router() {
		$db_driver = $this->_db_driver;
		$db_obj = ucfirst($db_driver).'DbLib';
		if(class_exists($db_obj)) {
			return new $db_obj();
		} else {
			return new MysqlDbLib();
		}
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