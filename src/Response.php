<?php
namespace phpapi;

/**
 * response
 * response the data for net 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Yzwu <distil@163.com>
 */


 class Response {
 	public $_body = '';
 	public function __construct() {
		

 	}

 	public function __destruct() {

 	}

 	public function get_body() {
        // return $this->_body;
        @header('Content-type: application/json;charset=utf-8');
        $res = array('ret' => 200, 'msg' => 'succ');
        echo json_encode($res);
 	}

 	public function set_body($res) {
 		if(empty($this->_body)) {
 			$this->_body = $res;
 		}
 	}
 }