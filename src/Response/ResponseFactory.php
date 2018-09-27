<?php
namespace PhpApi\Response;

use PhpApi\Standard\Factory\FactoryInterface;
/**
 * response factory
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


class ResponseFactory implements FactoryInterface {

    /**
     * @var $routeMode 默认，标准json传输
     */
	public $routeMode = 'json';
    public $routeObj = null;

	public function __construct() {
		$configObj = \PhpApi\Di::single()->config;
		$configApp = $configObj->appConfig;
		if (isset($configApp['ContentType']) && !empty($configApp['ContentType'])) {
			$this->routeMode = $configApp['ContentType'];
        }
        $this->load();
	}

	public function __desctruct() {}

	public function load() {
		$routeObjName = str_replace(' ', '', ucwords(strtolower(str_replace('-', ' ', $this->routeMode))));
        $className = '\\PhpApi\\Response\\' . $routeObjName . 'Response';
		if (class_exists($className)) {
			$this->routeObj = new $className();
		}

		return $this->routeObj;
    }
    
    /**
     * 创建body
     */
    public function setBody($data, ...$params) {
        if (method_exists($this->routeObj, 'setBody')) {
            $this->routeObj->setBody($data);
        } else {
            trigger_error('ROUTER ERROR! Please check your config<ContentType> or uri!');
        }
        
    }

	public function output() {
        if (method_exists($this->routeObj, 'output')) {
            $this->routeObj->output();
        } else {
            trigger_error('ROUTER ERROR! Please check your config<ContentType> or uri!');
        }
	}

 }