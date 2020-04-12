<?php
namespace PhpApi\Standard;


/**
 * 配置类
 * 处理配置的支持类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 interface Config {

    /**
     * 获取
     * 
     * @param string $key
     * @return mixed 
     */
    public function get($key);

    /**
     * 动态存储
     * 
     * @param string $key
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public function set($key, $value, $expire = 3600);

    /**
     * 动态存储删除
     * 
     * @param string $key
     * @return boolean
     */
    public function delete($key);
 }