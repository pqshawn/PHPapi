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

	protected $data = [];

	// 外包装
	protected $dataWrapper = array('data' => []);
	 
	public function __construct() {
		
 	}

 	public function __destruct() {

	}
	 
	protected function setHeaders() {
		@header('Content-type: application/json;charset=utf-8');
	}

	protected function getHeaders() {

	}

	protected function security() {
		
	}

	protected function getBody() {
		// @todo
		$retCode = array('ret' => 200, 'msg' => 'succ');
		$result = array_merge($retCode,$this->dataWrapper);
        echo json_encode($result);
 	}

 	public function setBody($data = [], ...$params) {
		// @todo request处理 
		$this->data = $data;
		$this->dataWrapper['data'] = $data;
	 }
	 
	public function output() {
		$this->setHeaders();
		$this->getBody();
	}
 }