<?php
namespace PhpApi;

use PhpApi\Model\ModelFactory;

use PhpApi\Di;

/**
 * base model
 * structure SQL, structure DATA
 * assume the ORM-base, ORM base it and makes the class about DB which more stronger later 
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

class Model extends ModelFactory {
	public $_db = '';
	public $_table = '';
	public function __construct($ori = false, $config = array()) {
		try {
			$this->load($ori, $config);
		} catch (Exception $e) {
			trigger_error($e.message, E_USER_ERROR);
		}
		
	}

	/**
	 * model 手动初始化
	 */
	public function load($ori = false, $config = array()) {
		if (empty($config)) {
			// 加载配置
			$configObj = Di::single()->config;
			$configApp = $configObj->appConfig;
			// 更多扩展，更多支持 @todo
			if (isset($configApp['Db']['Master'])) {
				$config = $configApp['Db']['Master'];
			} else {
				return new Exception('Error config for DB!'); 
			}
		}
		parent::load($config);
		// 自动计算表名
		if(!$ori) {
			$this->_table = $this->table();
			if(!$this->_table) trigger_error('Table name is error');
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

	/**
	 * data from Db
	 * @param string $line 
	 */
	public function retrieve($cols = '*', $where = '', $order = '', $offset = 0, $limit = -1) {
		if(empty($cols)) $clos = '*';
		$sql = "SELECT {$cols} FROM ".$this->_table;
		$where = $this->where($where);
		$order = $this->order($order);
		// offset,limit 不给默认值，防止误会，但是系统最大值是没关系的，希望开发者自己设限
		if ($offset >= 0 && $limit > 0) {
			$lim = " limit {$offset},{$limit}";
		} elseif ($limit > 0) {
			$lim = " limit {$limit}";
		}
        $sql .= $where . $order . $lim;

		$data = $this->_db->select($sql);
		return $data;
	}

	public function table($table_name = null) {
		if(empty($table_name)){
			$ex_res = $this->classExplode(get_class($this));
			$table = implode('_', $ex_res);
			$table_name = $this->_db_prefix.strtolower($table);
		}
		$res = parent::table($table_name);
		if(!$res) trigger_error('Table name is null or no exist!', E_USER_ERROR);
		else return $table_name;
	}

	public function filter(&$data) {
		if(is_array($data) && !empty($data)) {
			foreach($data as $k => &$v) {
				$v = $this->quote($v);
			}
			return $data;
		} else {
			return false;
		}
	}

	public function where($where = '', $retObj = 0) {
		$str = ' WHERE 1 ';
		if(is_array($where) && !empty($where)) {
			$where = $this->filter($where);
			$operate = '='; // @todo 扩展操作符
			foreach($where as $wk => $wv) {
				// 默认带上别名
				$alias = '';
				if(isset($this->_options['alias']) && $this->_options['alias']) {
					$alias = $this->_options['alias'] . '.';
				}

				$str .= " AND ".$alias."`".$wk."` ".$operate." ".$wv;
			}
		} else {
			$str .= $where;
		}

		if($retObj == 1) {
			$this->_options['where'] = $str;
			return $this;
		}

		return $str;
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

	public static function classExplode($class_name) {
		$nspace = $class_name;
		$nspace_path = str_replace('\\', '/', $nspace);
		$nspace_arr = explode('/', $nspace_path);
		if(count($nspace_arr) > 1) {
			$class_name = array_pop($nspace_arr);
		}
		$ex_res = preg_split("/(?=[A-Z])/", $class_name, 0, PREG_SPLIT_NO_EMPTY);
		return $ex_res;
	}

	public function find($cols = '*', $where = '', $order = '', $offset = 0, $limit = -1) {
		$findRes = $this->retrieve($cols, $where, '', 0, 1);
		$res = [];
		if (!empty($findRes)) {
			$res = $findRes[0];
		}
		return $res;
	}

}