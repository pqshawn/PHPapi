<?php
namespace PhpApi;

/**
 * normal router
 * when you choose normal router or no choose
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
*/

class Router {

	public $_url_model = 'normal';
	private $_fmapper_url = array();
	public static $_route_pc = null;
	public $_requst = '';
	public $_response = '';

	public function __construct() {
		$urlmode = 'normal'; 
		if(defined('URL_MODEL')) {
			$urlmode = URL_MODEL;
		}
		$this->_url_model = $urlmode;
		if(empty($this->_fmapper_url) && empty($this->_fmapper_exname)) {
			require_once(ROOT_DIR.'/config/mapper.php');
			$this->_fmapper_url = $_mapper_url;
		}
		$this->_request = Blood::single('library\RequestLib');
		$this->_response = Blood::single('library\ResponseLib');
	}

	public function __desctruct() {}

	public static function load() {
		if(empty(self::$_route_pc)) {
			self::$_route_pc = new RouterFactoryLib(); 
		}
		return self::$_route_pc;
	}

	public function router() {
		$urlmode = $this->_url_model;
		$urlm_obj = ucfirst(strtolower($urlmode)).'RouterLib';
		if(class_exists($urlm_obj)) {
			$router_obj = new $urlm_obj($this->_fmapper_url);
		} else {
			$router_obj = new NormalRouterLib($this->_fmapper_url);
		}
		return $router_obj;
	}

	public function dispatch() {
		$model_info = $this->router()->parse_url();
		if(empty($model_info)) {
			//$this->_response->res('404');
			echo ('404 The page is not exist!');exit;
			//@todo 由<接收响应机制>
		}
		$app = $model_info['app'];
		$module = $model_info['module'];
		$action = $model_info['action']?$model_info['action']:($model_info['parameter']['action']?$model_info['parameter']['action']:'');
		$default = $model_info['default'];
		$parameter = $model_info['parameter'];
		$this->_request->set_param($parameter, $default);
		$controller = ucfirst($module).ucfirst($app).'Con';
		//call function and write response in the class reponse
		if(!method_exists(Blood::single($controller), $action)) {
			echo ('404 The page is not exist!');exit;
		}else {
			Blood::single($controller)->$action();
		}
		//$this->_response->sendhttphead();
		$response = $this->_response->get_body();
		echo $response;
	}

}