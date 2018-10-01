<?php
namespace PhpApi\Cache;

use PhpApi\Standard\Cache\CacheInterface;

/**
 * redis driver
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Yzwu <Ldos.net>
 */

final class Redis implements CacheInterface {
	private $_link_res = null;
	private $_cache_config = array();
	
	public function __construct() {

	}

	/**
	 * set config
	 */
	public function setConfig($conf = array()) {
		$this->_cache_config = array(
			'host' => isset($conf['host'])? $conf['host'] : '',
			'user' => isset($conf['user'])? $conf['user'] : '',
			'pwd' => isset($conf['password'])? $conf['password'] : '',
			'name' => isset($conf['db'])? $conf['db'] : '',
			'port' => isset($conf['port'])? $conf['port'] : '',
			'charset' => isset($conf['charset'])? $conf['charset'] : ''
		);
	}

	private function user_connect($cache_config = array(), $new_link = true) {
		$link = $this->_link_res;
		if(is_object($link) && !$new_link) return $link;
		$link = $this->_connect($cache_config, $new_link);
		return $link;
	}

	public function _connect($cache_config = array(), $new_link = false) {
		$db_link = '';
		if(empty($cache_config)) {
			$cache_config = $this->_cache_config;
		}

		$this->_link_res = new \Redis();
	    $this->_link_res->connect($cache_config['host'], $cache_config['port']);
	    if (!is_object($this->_link_res)) {
	        trigger_error('redis init error', E_USER_WARNING);
	    }
		
		return $this->_link_res;
	}

	public function set($key, $val, $expire = 3600) {
		if(empty($this->_link_res)) {
			$this->user_connect();
		}
		
		$res = $this->_link_res->set($key, $val, $expire);
		return $res;
	}

	public function get($key) {
		if(empty($key)) {
			return '';
		}
		if(empty($this->_link_res)) {
			$this->user_connect();
		}
		
		$res = $this->_link_res->get($key);
		return $res;
	}

	public function delete(...$keys) {
		if(empty($keys)) {
			return false;
		}
		
		$res = $this->_link_res->del($keys);
		return $res;
	}
}