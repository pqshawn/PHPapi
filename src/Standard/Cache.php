<?php
namespace PhpApi\Interfaces;


/**
 * 缓存类
 * 处理缓存的支持类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 interface Cache {

    /**
     * 获取缓存
     * 
     * @param string $key
     * @return mixed 
     */
    public function get($key);

    /**
     * 设置缓存
     * 
     * @param string $key
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public function set($key, $value, $expire = 3600);

    /**
     * 删除缓存
     * 
     * @param string $key
     * @return boolean
     */
    public function delete($key);
 }