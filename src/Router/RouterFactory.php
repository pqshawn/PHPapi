<?php
namespace PhpApi\Router;

use PhpApi\Router\Auto;
use PhpApi\Standard\Factory\FactoryInterface;

/**
 * router factory
 * 暂不固定，实现Auto即可
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
*/

class RouterFactory implements FactoryInterface {

	/**
	 * router模式：默认Auto,Dot,Pathinfo
	 */
	public $routeMode = 'Auto';
	public $routeObj = null;
	public $requst = '';
	public $response = '';

	public function __construct() {
		$configObj = \PhpApi\Di::single()->config;
		$configApp = $configObj->appConfig;
		if (isset($configApp['RouteType']) && !empty($configApp['RouteType'])) {
			$this->routeMode = $configApp['RouteType'];
		}
	}

	public function __desctruct() {}

	public function load() {
		$routeObjName = ucfirst(strtolower($this->routeMode));
		$className = '\\PhpApi\\Router\\' . $routeObjName . 'Router';
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