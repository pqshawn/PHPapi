<?php
namespace PhpApi\Router;

use PhpApi\Router\Auto;

/**
 * router factory
 * 暂不固定，实现Auto即可
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
*/

class RouterFactory {

	/**
	 * router模式：默认Auto,Dot,Pathinfo
	 */
	public $routeMode = 'Auto';
	public $routeObj = null;
	public $requst = '';
	public $response = '';

	public function __construct() {
		if(defined('URL_MODEL')) {
			// config 类 @todo
			// $urlMode = URL_MODEL;
			// $this->urlMode = $urlMode;
		}
		
		// $this->_request = Blood::single('library\RequestLib');
		// $this->_response = Blood::single('library\ResponseLib');
	}

	public function __desctruct() {}

	public function load() {
		$routeObjName = ucfirst(strtolower($this->routeMode));
		$className = '\\PhpApi\\Router\\'.$routeObjName;
		if (class_exists($className)) {
			$this->routeObj = new $className();
		}

		return $this->routeObj;
	}

	public function dispatch($retData = false) {
		$result = $this->routeObj->dispatch();
		if ($retData) {
			return $result;
		}

		// //$this->_response->sendhttphead();
		// $response = $this->_response->get_body();
		// echo $response;
	}

}