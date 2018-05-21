<?php
namespace PhpApi;

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
		$result = $this->dataWrapper;

		if (!isset($this->dataWrapper['ret'])) {
			$retCode = array('ret' => 200, 'msg' => 'succ');
			$result = array_merge($retCode,$this->dataWrapper);
		}

        echo json_encode($result);
 	}

 	public function setBody($data = [], ...$params) {
		// @todo request处理 
		$this->data = $data;

		// 如有用户自定义语言包，重新计算下
		$configObj = \PhpApi\Di::single()->config;
		$lang = $configObj->lang;
		$codeStatus = $configObj->codeStatus;
		$appName = $configObj->appName;

		if (isset($data['ret']) && is_string($data['ret'])) {
			// 重新定义错误码
			if (isset($codeStatus[$appName][$data['ret']])) {
				$this->dataWrapper['ret'] = $codeStatus[$appName][$data['ret']];
			}
			// 重新定义消息
			if (isset($lang[$data['ret']])) {
				$this->dataWrapper['msg'] = $lang[$data['ret']] ;
			}
			unset($data['ret']);
		}

		$this->dataWrapper['data'] = $data;

	 }
	 
	public function output() {
		$this->setHeaders();
		$this->getBody();
	}
 }