<?php
/**
 * mysql driver
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Yzwu <Ldos.net>
 */

final class MysqlDbLib implements DbInterfaceLib {
	private $_link_res = '';
	private $_connect_type = '';

	public function __construct() {

	}

	private function user_connect($db_config = array(), $new_link = true) {
		$link = $this->_link_res;
		if(is_resource($link) && !$new_link) return $link;
		$link = $this->_connect($db_config, $new_link);
		return $link;
	}

	public function _connect($db_config = array(), $new_link = false) {
		$db_link = '';
		if(empty($db_config)) {
			$db_config = array('host' => DB_HOST,
							   'user' => DB_USER,
							   'pwd' => DB_PWD,
							   'name' => DB_NAME,
							);
		}

		$conn_flag = false;
		if(defined(DB_PERSISTENT_CONNECT)) {
			if(DB_PERSISTENT_CONNECT === 1) {
				$db_link = mysql_pconnect($db_config['host'], $db_config['user'], $db_config['pwd']);
			} else {
				$conn_flag = true;	
			}
		} else {
			$conn_flag = true;
		}
		if($conn_flag) { 
			if($new_link) {
				$db_link = mysql_connect($db_config['host'], $db_config['user'], $db_config['pwd'], true);
			} else {
				$db_link = mysql_connect($db_config['host'], $db_config['user'], $db_config['pwd']);
			}
			
		}
		if(!$db_link) {
			trigger_error('Cannot connect the Database!', E_USER_WARNING);
		}
		mysql_select_db($db_config['name'], $db_link);
		if(defined('DB_CHARSET')) {
			mysql_query('SET NAMES \''.DB_CHARSET.'\'', $db_link);
		}
		$this->_link_res = $db_link;
		return $db_link;
	}

	public function select($sql, $new_link = false) {
		if(empty($this->_link_res)) {
			$this->user_connect();
		}
		$db_link = $this->_link_res;
		$rs = $this->exec($sql, $db_link, $new_link);
		$data = array();
		if($rs['rs']) {
			while($row = mysql_fetch_assoc($rs['rs'])) {
				$data[] = $row;
			}
			mysql_free_result($rs['rs']);
			return $data;
		} else {
			return false;
		}
	}

	public function create($sql) {}
	public function update() {}
	public function retrieve() {}
	public function delete() {

	}

	public function exec($sql, $db_link = false, $new_link = false) {
		if(!is_resource($this->_link_res) || !is_resource($db_link)) {
			$db_link = $this->user_connect('', $new_link);
		}
		
		if($rs = mysql_query($sql, $db_link)) {
			$db_res = array('rs' => $rs, 'sql' => $sql);
			return $db_res;
		} else {
			trigger_error('SQL:'.mysql_errno($db_link).' in '.$sql, E_USER_WARNING);
			return false;
		}
	}
	public function count() {}
	public function begin() {}
	public function commit() {}
	public function rollback() {}

	public function quote($string){
		$result = mysql_escape_string($string);
        if(!$result){
            $result = addslashes($string);
        }
        return "'" . $result . "'";
    }

	public function close() {
		if(is_resource($this->_link_res)) {
			if(mysql_close($this->_link_res)) {
				$this->_link_res = '';
				return true;	
			} else {
				trigger_error('DB closed failure!', E_USER_WARNING);
			}	
			return false;
		}
		$this->_link_res = '';
		return true;
	}
}

