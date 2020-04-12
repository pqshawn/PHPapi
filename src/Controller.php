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
	
	/**
     * @var request 各个数据集合
     */
	public static $requestData = [];
	
	public $params = [];

	protected $action = '';

	protected $filerRequest = [];

	public function __construct($actionName = '') {
		$this->init();
		$this->initialize($actionName);
		$this->action = $actionName;
	}

	public function beforeAction() {
		// 处理参数，启动request工厂，接收request值，并反射到requestData上
		Di()->request->generate();
		$this->params = isset(self::$requestData['RequestBody'])? self::$requestData['RequestBody'] : [];

		self::$requestData = null;
		// 用户自定义的也需要处理
		if (method_exists($this, 'beforeUserAction') ) {
			$this->beforeUserAction();
		}
		// 过滤检测参数（根据用户自定义规则）
		$this->filerRequest();
	}

	public function afterAction() {
		
	}

	/**
	 * 拦截，不合规则的参数
	 */
	public function filerRequest() {
		$filerRequest = $this->filerRequest;
		Di()->filter->filterCombineParams($filerRequest, $this->params, $this->action);
		// 开始执行
		Di()->filter->start($filerRequest);
	}

	/**
	 * 配置预处理
	 */
	public function init() {
		// 前面有确切的实例化，建议直接调用对象
		Di()->request = '\\PhpApi\\Request\\RequestFactory';
		Di()->response = '\\PhpApi\\Response\\ResponseFactory';
		Di()->model = '\\PhpApi\\Model';
		// 取消model配置，每个controller不一定要model操作
		// $this->modelConf();
		Di()->filter = '\\PhpApi\\Filter';
		
	}

	/**
	 * 用户自定义初始化
	 */
	protected function initialize($actionName = '')
    {}


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
		Di()->response->setBody($data, []);
		// response处理
		Di()->response->output();
	}






	public function __destruct() {}


}