<?php
namespace PhpApi;

use PhpApi\Standard\Controller\ControllerAbstract;
use PhpApi\Standard\Middleware\MiddlewareTraits;
use PhpApi\Di;
/**
 * base controllers
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Shawn Yu <pggq@outlook.com>
 */

class Controller extends ControllerAbstract {

	// 用traits方式调用中间件
	use MiddlewareTraits;
	
	protected $_vars = array();

	public function __construct() {
		
		$this->init();

	}

	public function beforeAction() {

	}

	public function afterAction() {
		
	}

	/**
	 * 配置预处理
	 */
	public function init() {
		// 前面有确切的实例化，建议直接调用对象
		Di::single()->request = '\\PhpApi\\Request';
		Di::single()->response = '\\PhpApi\\Response';
		Di::single()->model = '\\PhpApi\\Model';
		// 取消model配置，每个controller不一定要model操作
		// $this->modelConf();
	}

	/**
	 * Model配置
	 */
	public function modelConf() {
		
	}


	/**
	 * 输出类
	 */
	public function output($data = []) 
	{	
		// 前面有确切的实例化，建议直接调用对象
		Di::single()->response->setBody($data, []);
		// response处理
		Di::single()->response->output();
	}






	public function __destruct() {}


}