<?php
/**
 * base model
 * structure SQL, structure DATA
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Yzwu <Ldos.net>
 */

class ModelLib extends DbFactoryLib implements ModelInterfaceLib {
	public $_db = '';
	public $_table = '';
	public function __construct($ori = false) {
		parent::__construct();
		if(!$ori) {
			$this->_table = $this->table();
		}
	}

	public function create($data) {
		$this->filter($data);
		$key = implode(',', array_keys($data));
		$value = implode(',', $data);
		$sql = "INSERT INTO ".$this->_table." (".$key.") VALUES (".$value.")";
		$res = parent::create($sql);
		return $res;
	}

	public function update($sql) {

	}

	/**
	 * data from Db
	 * @param string $line 
	 */
	public function retrieve($cols = '*', $where = '', $order = '', $offset = 0, $limit = -1) {
		if(empty($cols)) $clos = '*';
		$sql = "SELECT {$cols} FROM ".$this->_table;
		$where = $this->where($where);
		$order = $this->order($order);
		($offset > 0)? $offset = $offset:$offset = 0;
		($limit > 0)? $limit = $limit:$limit = 18446744073709551615;
		$lim = " limit {$offset},{$limit}";
                $sql .= $where . $order . $lim;

		$data = $this->_db->select($sql);
		return $data;
	}


	public function delete($sql) {

	}

	public function table($table_name = null) {
		if(empty($table_name)){
			$ex_res = NutrientLib::class_explode(get_class($this));
			$table = array_shift($ex_res);
			$table_name = $this->_db_prefix.lcfirst($table);
		}
		$res = parent::table($table_name);
		if(!$res) trigger_error('Table name is null or no exist!', E_USER_WARNING);
		else return $table_name;
	}

	public function filter(&$data) {
		if(is_array($data) && !empty($data)) {
			foreach($data as $k => &$v) {
				$v = $this->_db->quote($v);
			}
			return $data;
		} else {
			return false;
		}
	}

	public function where($where = '') {
		if(is_array($where) && !empty($where)) {
			$where = $this->filter($where);
			$where_sql = ' WHERE 1 ';
			$i = 0;
			foreach($where as $wk => $wv) {
				$wk_arr = explode('|', $wk);
				if(isset($wk_arr[0])) $k = $wk_arr[0];
				if(isset($wk_arr[1])) $o = $wk_arr[1];
				if(isset($wk_arr[2])) $and_or = strtoupper($wk_arr[2]);
				$i > 0? (!in_array($and_or, array('AND', 'OR'))? $and_or = 'AND' : '') : $and_or = 'AND';
				if(isset($k)) {
					switch ($o) {
						case 'like':
							$operate = 'like';
							$wv = preg_replace('/\'([\s\S]+)\'/', '\'%${1}%\'', $wv);
							break;
						
						default:
							$operate = '=';
							break;
					}
					$where_sql .= " ".$and_or." `".$k."` ".$operate." ".$wv;
					$i++;
				}
			}
			return $where_sql;
		} else if(is_string($where)) {
			//@todo filter preg_match
		} else {
			$where = '';
			//@todo filter other type
		}
		return $where;
	}

	public function order($orderstr = '') {
		if(is_string($orderstr) && !empty($orderstr)) {
			return ' ORDER BY '.$orderstr;
		} else {
			//@todo
		}
	}

	public function exec($data) {
		$res = parent::create($data);
		return $res;
	}

}