<?php
namespace PhpApi\Factory;

/**
 * router factory
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
*/

class Frouter {

	/**
	 * router模式：Auto,Dot,Pathinfo
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
		if(class_exists($routeObjName)) {
			$this->routeObj = new $this->routeObj();
		}

		return $this->routeObj;
	}

	public function dispatch() {
		$mapper = $this->routeObj->mapper;
		
		Di::single()->controller = $mapper['controller'];


		if (class_exists($mapper['controller'])) {
			
		}


		// //$this->_response->sendhttphead();
		// $response = $this->_response->get_body();
		// echo $response;
	}

}