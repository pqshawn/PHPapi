<?php
namespace PhpApi\Dao;

use PhpApi\Standard\Model\ModelInterface;

/**
 * mysqli driver
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Yzwu <Ldos.net>
 */

final class Mysqli implements ModelInterface {
	private $_link_res = '';
	private $_connect_type = '';
	private $_db_config = array();
	
	public function __construct() {

	}

	/**
	 * set config
	 */
	public function setConfig($conf = array()) {
		$this->_db_config = array(
			'host' => isset($conf['host'])? $conf['host'] : '',
			'user' => isset($conf['user'])? $conf['user'] : '',
			'pwd' => isset($conf['password'])? $conf['password'] : '',
			'name' => isset($conf['db'])? $conf['db'] : '',
			'charset' => isset($conf['charset'])? $conf['charset'] : ''
		);
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
			$db_config = $this->_db_config;
		}
		
		$db_link = mysqli_connect($db_config['host'], $db_config['user'], $db_config['pwd'], $db_config['name']);

		if(!$db_link) {
			trigger_error('Cannot connect the Database!', E_USER_WARNING);
		}
		if(isset($db_config['charset'])) {
			mysqli_query($db_link, 'SET NAMES \''.$db_config['charset'].'\'');
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
			while($row = mysqli_fetch_assoc($rs['rs'])) {
				$data[] = $row;
			}
			mysqli_free_result($rs['rs']);
			return $data;
		} else {
			return false;
		}
	}

	public function create($sql) {}
	public function update($sql) {}
	public function retrieve($sql) {}
	public function delete($sql) {

	}

	public function exec($sql, $db_link = false, $new_link = false) {
		
		if(!is_resource($this->_link_res) || !is_resource($db_link)) {
			$db_link = $this->user_connect('', $new_link);
		}
		
		if($rs = mysqli_query($db_link, $sql)) {
			$db_res = array('rs' => $rs, 'sql' => $sql);
			return $db_res;
		} else {
			trigger_error('SQL:'.mysqli_errno($db_link).' in '.$sql, E_USER_WARNING);
			return false;
		}
	}
	public function count() {}
	public function begin() {}
	public function commit() {}
	public function rollback() {}

	public function quote($string){

		$result = @mysqli_real_escape_string($this->_link_res, $string);
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