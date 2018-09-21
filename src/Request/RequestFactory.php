<?php
namespace PhpApi\Request;

use PhpApi\Standard\Factory\FactoryInterface;
use PhpApi\Standard\Reflection as PhpApiReflection;
/**
 * request factory
 * 对于通用的参数，或者没有特殊需求，我们无需再具体到生产线上处理数据(如创建json生产线，再获取数据等)，直接让pool返射到controller类
 * [反射+注入+迭代]在大型复杂业务中，特别是对迭代到一定程度的代码是非常受用的，让复杂的数据组件间的依赖关系变得轻松。
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


class RequestFactory implements FactoryInterface {

    /**
     * @var $routeMode 默认，标准json传输
     */
	public $routeMode = 'json';
    public $routeObj = null;

    /**
     * 数据池名
     */
    public $poolName = '\\PhpApi\\Standard\\Request\\RequestPoolParse';

    /**
     * 需要池里的某类型数据-设置对象组,
     * 这里是通用，如要单用，请另设一个参数，并重写generateMyData()
     */
    public $requestDataObjs = array(
        '\\PhpApi\\Standard\\Request\\Pool\\RequestBody',
        '\\PhpApi\\Standard\\Request\\Pool\\RequestInfo',
        '\\PhpApi\\Standard\\Request\\Pool\\HttpClientInfo',
    );

	public function __construct() {
		// $configObj = \PhpApi\Di::single()->config;
		// $configApp = $configObj->appConfig;
		// if (isset($configApp['ContentType']) && !empty($configApp['ContentType'])) {
		// 	$this->routeMode = $configApp['ContentType'];
        // }
        // $this->load();
	}

	public function __desctruct() {}

	public function load() {
		$routeObjName = str_replace(' ', '', ucwords(strtolower(str_replace('-', ' ', $this->routeMode))));
        $className = '\\PhpApi\\Request\\' . $routeObjName . 'Request';
		if (class_exists($className)) {
			$this->routeObj = new $className();
		}

		return $this->routeObj;
    }

    /**
     * generate数据
     * 通用生成，如果是个别只用到某一个，重写generateMyData
     * 如：只需要Pool\RequestBody ， 则
     * @return void
     */
    public function generate() {
        $flection = new PhpApiReflection();
        $poolIterator = $flection->make($this->poolName);
        // 注入对象
        $requestDataTypeNames = [];
        $Di = \PhpApi\Di::single();
        foreach($this->requestDataObjs as $obj) {
            $objStrToArr = explode('\\', $obj);
            $objName = array_pop($objStrToArr);
            $Di->$objName = $obj;
            $requestDataTypeNames[] = $objName;
        }
        // 数据类型 加进 数据池
        $poolIterator->addRequestType($requestDataTypeNames);
        // 迭代执行各类型
        foreach ($poolIterator as $pk => $pv) {}
        
    }

    /**
     * generate单个类型，或几种特定类型数据
     */
    public function generateMyData() {
        
    }
    


 }