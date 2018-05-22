<?php
namespace PhpApi;

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
        $configObj = Di::single()->config;
        $configApp = $configObj->config;
        $appName = $configObj->appName;
        $config = $configApp[$appName]['Debug'];
        
    }
    /**
     * 反射处理SQL的结果
     */
    public function debugSql() {

    }
 }