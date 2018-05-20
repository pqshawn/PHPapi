<?php
namespace PhpApi;

use PhpApi\Standard\Controller\ControllerAbstract;
use PhpApi\Standard\Middleware\MiddlewareTraits;
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
		Di::single()->request = '\\PhpApi\\Request';
		Di::single()->response = '\\PhpApi\\Response';
	}

	/**
	 * 输出类
	 */
	public function output($data = []) 
	{	
		Di::single()->response->setBody($data, []);
		// response处理
		Di::single()->response->output();
	}






	public function __destruct() {}


}