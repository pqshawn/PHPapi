<?php
namespace PhpApi\Request;

use PhpApi\Standard\Factory\FactoryInterface;
use PhpApi\Standard\Reflection as PhpApiReflection;
/**
 * request factory
 * 对于通用的参数，我们无需要再具体到生产线上拿了，如requestHeader等，直接让pool返射到工厂
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
    public $poolName = 'RequestPoolParse';


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
     * 
     * @return void
     */
    public function generate() {
        $flection = new PhpApiReflection();
        $poolIterator = $flection->make($this->poolName);
        foreach ($poolIterator as $pi) {}
        
    }
    


 }