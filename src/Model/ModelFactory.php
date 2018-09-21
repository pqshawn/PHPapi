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
	// 查询表达式
	protected $_selectSql = 'SELECT %FIELD% FROM %TABLE% %ALIAS% %JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%%COMMENT%';
	protected $_options = array();
	// 链操作方法列表
    protected $_methods = array('strict', 'order', 'alias', 'having', 'group', 'lock', 'distinct', 'auto', 'filter', 'validate', 'result', 'token', 'index', 'force');

	public function __construct($confDb = array()) {
		$this->load($confDb);
	}

	public function load($confDb = array()) {
		if (empty($confDb)) return null;
		if (isset($confDb['prefix'])) {
			$this->_db_prefix = $confDb['prefix'];
		} else {
			$this->_db_prefix = '';
		}
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

	public function update($param, $where) {
		if(empty($where)) return false;
		$this->filter($param);	
		$exec_sql = 'UPDATE `'. $this->_table . '` SET ';
		if(is_array($param) && !empty($param)) {
			foreach($param as $wk => $wv) {
				$exec_sql .= " `".$wk."` = ".$wv.',';
			}
			$comma = substr($exec_sql, -1);
			if($comma == ',') {
				$exec_sql = rtrim($exec_sql, ',');
			}
		}
		$exec_sql .= $this->where($where);
		$result = $this->_db->exec($exec_sql);
		return $result;
	}
	
	public function retrieve($sql) {
		$data = $this->_db->select($sql);
		return $data;
	}
	public function delete($where) {
		$where_str = $this->where($where);
		if(empty($where_str)) {
			return false;
		}
		$sql = "DELETE FROM ".$this->_table.$where_str;
		$result = $this->_db->exec($sql);
		return $result;
	}

	public function table($tablename = null) {
		if(!empty($tablename)) {
			$showtab = "SHOW TABLES LIKE '%$tablename%'";
			$result = $this->_db->select($showtab);
			return $result;
		} else {
			return false;
		}
	}

	public function query($getSqlStr = 0, $sql = '', $options = '') {
		if(empty($sql)) {
			$options = $this->_options;
		}
		$sql = $this->parseSql($sql, $options);
		$this->_options = array();
		if($getSqlStr == 1) {
			return $sql;
		}

		$this->_sql->format($sql);
		$result = $this->_db->query($this->_sql, $this->_keytype);
		return $result;
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

	/**
	 * 添加引号
	 */
	public function quote($string){
		$result = '';
		if(is_string($string)) {
			$result = addslashes($string);
			return "'" . $result . "'";
		} else {
			return $string;
		}
        
	}
	
	/**
	 * 条件化语句sql
	 * 
	 * @param array $options 表达式
	 *
	 * @return string
	 */
	public function parseSql($sql, $options = array()){
		if(empty($sql)) {
			$sql = $this->_selectSql;
		}
		$sql = str_replace(
			array('%TABLE%', '%FIELD%', '%ALIAS%', '%JOIN%', '%WHERE%', '%GROUP%', '%HAVING%', '%ORDER%', '%LIMIT%', '%UNION%', '%COMMENT%'),
			array(
				(!empty($options['table']) ? $options['table'] : $this->_table),
				$this->parseField(!empty($options['field']) ? $options['field'] : '*'),
				$this->parseAlias(!empty($options['alias']) ? $options['alias'] : ''),
				$this->parseJoin(!empty($options['join']) ? $options['join'] : ''),
				$this->parseWhere(!empty($options['where']) ? $options['where'] : ''),
				$this->parseGroup(!empty($options['group']) ? $options['group'] : ''),
				$this->parseHaving(!empty($options['having']) ? $options['having'] : ''),
				$this->parseOrder(!empty($options['order']) ? $options['order'] : ''),
				$this->parseLimit(!empty($options['limit']) ? $options['limit'] : ''),
				$this->parseUnion(!empty($options['union']) ? $options['union'] : ''),
				$this->parseComment(!empty($options['comment']) ? $options['comment'] : '')
			), $sql);
		return $sql;
	}

	public function __call($method, $args) {
		if (in_array(strtolower($method), $this->_methods, true)) {
            $this->_options[strtolower($method)] = $args[0];
            return $this;
        } else {
        	$flag = substr($method, 5);
        	if(!empty($flag)) {
        		$flag = strtolower($flag);
        		if(isset($this->_options[$flag]))
        			return $this->_options[$flag];
        	}
        }
	}

	public function getLastId(){
        return $this->_db->getLastId();
    }

}