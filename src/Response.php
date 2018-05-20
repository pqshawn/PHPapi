<?php
/**
 * response
 * response the data for net 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Yzwu <distil@163.com>
 */

 namespace library;

 class ResponseLib {
 	public $_body = '';
 	public function __construct() {

 	}

 	public function __destruct() {

 	}

 	public function get_body() {
 		return $this->_body;
 	}

 	public function set_body($res) {
 		if(empty($this->_body)) {
 			$this->_body = $res;
 		}
 	}
 }