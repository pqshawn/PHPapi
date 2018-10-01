<?php
namespace PhpApi\Standard\Cache;


/**
 * 缓存接口

 * 处理缓存的支持类规范
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 interface CacheInterface {

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
     * @param ... $keys
     * @return boolean
     */
    public function delete(...$keys);
 }