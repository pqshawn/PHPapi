<?php
namespace PhpApi;


/**
 * 主启动类
 * kernel启动
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class Kernel {
	/**
	 * @var 全局单例变量
	 */
	private static $_single_obj = array();

	public function __construct() {
		Di()->router = '\\PhpApi\\Router\\RouterFactory';
	}

	/**
	 * 注入式配置
	 * 
	 * @param string $rootDir
	 * 
	 * @return void
	 */
	public static function config($configDir = '', $appName = 'App') {
		Di()->config = new \PhpApi\Config($configDir);
		Di()->config->main($appName);
	}


	/**
	 * 分发器
	 */
	public static function dispatch() {
		// xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
		try{
			set_error_handler(array('\PhpApi\Exception', 'user_error_handler'));
			register_shutdown_function(array('\PhpApi\Exception', 'callRegisteredShutdown'));

			$router = Di()->router;
			
			$routerInstance = $router->load();
			
			$routerInstance->dispatch(true);
		} catch (\Exception $e) {
			\PhpApi\Exception::user_exception_handle($e);
		}
		// $data = xhprof_disable();
		// include_once "/data/www/xhprof/xhprof_lib/utils/xhprof_lib.php";
		// include_once "/data/www/xhprof/xhprof_lib/utils/xhprof_runs.php";
		// $objXhprofRun = new \XHProfRuns_Default(); 
		// $objXhprofRun->save_run($data, "xhprof");
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

