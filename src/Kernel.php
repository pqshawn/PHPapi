<?php
namespace PhpApi;

use PhpApi\Helper;
/**
 * 主启动类
 * kernel启动
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

class Kernel {
	/**
	 * @var 全局单例变量
	 */
	private static $_single_obj = array();

	public function __construct() {
		\PhpApi\Di::single()->router = '\\PhpApi\\Router\\RouterFactory';
	}

	public static function dispatch() {
		$router = \PhpApi\Di::single()->router;
        $routerInstance = $router->load();
        $ret = $routerInstance->dispatch(true);
		
		// Di()->request->parseRequest();

		// try{
		// 	//config
		// 	//router
		// 	$router_pc = RouterFactoryLib::load();
		// 	$router_pc->dispatch();
		// } catch(Exception $e) {
		// 	self::user_exception_handle($e);
		// 	// echo 'Message: ' .$e->getMessage();
		// }

	}
	
	/**
	 *  全局单例
	 *  不带懒加载功能，可以用依赖注入懒加载
	 */
	public static function single($class_name, $key = null) {
		if(!empty($key)) {
			$md5Key = md5($key);
		} else {
			$md5Key = md5($class_name);
		}

		if(!empty(self::$_single_obj[$md5Key])) {
			return self::$_single_obj[$md5Key];
		}
		else {
			self::$_single_obj[$md5Key] = new $class_name();
		}
		
		return self::$_single_obj[$md5Key];
	}


}

