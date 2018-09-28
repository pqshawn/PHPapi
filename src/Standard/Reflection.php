<?php
namespace PhpApi\Standard;

/**
 * reflection 处理类
 * 主要处理一些需要逆向解析的功能，诸如解析性能，debug，依赖注入 等等
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Yzwu <distil@163.com>
 */


 class Reflection {

    public static $debugSql = [];

    public $debugSwitch = false;

    public function getConfig() {
        // $configObj = Di::single()->config;
        // $configApp = $configObj->config;
        // $appName = $configObj->appName;
        // $config = $configApp[$appName]['Debug'];
        
    }
    /**
     * 反射处理SQL的结果
     */
    public function debugSql() {

    }

    /**
     * 构建类的对象
     * 
     * @param string $className
     * 
     * @param object
     */
    public function make($className) {
        $reflectionClass = new \ReflectionClass($className);
        $constructor = $reflectionClass->getConstructor();
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        return $reflectionClass->newInstanceArgs($dependencies);
    }
        
    /**
     * 递归解析依赖
     */
    public function getDependencies($parameters) {
        $dependencies = [];
        foreach($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if (is_null($dependency)) {
                if($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    $dependencies[] = '0';
                }
            } else {
                $dependencies[] = $this->make($parameter->getClass()->name);
            }
        }
        return $dependencies;
    }





 }