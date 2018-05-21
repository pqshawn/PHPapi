<?php
/**
 * Standard里面提供设计规范
 */
namespace PhpApi\Standard;

use ArrayAccess;
use Closure;

/**
 * 依赖注入类
 * dependence injection class
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

abstract class DependenceIn implements ArrayAccess {

    /**
     * 对象资源集合
     * @param array $source
     */
    protected $source = [];

    /**
     * 每个key命中次数,只在第一次时加载服务
     * @param array $hitCount
     */
    protected $hitCount = [];

    public function __construct() {
    }

    /**
     * 自定义set方法
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function set($key, $val) {
        if (!isset($this->source[$key])) {
            $this->source[$key] = $val;
            $this->hitCount[$key] = 0;
        }
    }

    /**
     * 自定义get方法
     * @return mixed
     */
    public function get($key, $default = '') {
        if (!isset($this->source[$key])) {
            $this->source[$key] = $default;
            $this->hitCount[$key] = 0;
        }
        if ($this->hitCount[$key] == 0) {
            $this->source[$key] = $this->initService($this->source[$key]);
        }
        $this->hitCount[$key]++;

        return $this->source[$key];
    }

    /**
     * 自定义get方法
     * @param mixed $data
     * @return mixed
     */
    public function initService($serviceVal = '') {
        $service = '';
        if (is_string($serviceVal) && class_exists($serviceVal)) {
            $service =  new $serviceVal();
        } else if ($serviceVal instanceOf Closure) {
            $service = $serviceVal();
        } else {
            $service = $serviceVal;
        }

        return $service;
    }

    /**
     * 系统魔术set
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function __set($key, $val) {
        $this->set($key, $val);
    }

    /**
     * 系统魔术get
     * @return mixed
     */
    public function __get($key) {
        return $this->get($key, '');
    }

    public function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    public function offsetGet($offset) {
        return $this->get($offset, NULL);
    }

    public function offsetUnset($offset) {
        unset($this->source[$offset]);
    }

    public function offsetExists($offset) {
        return isset($this->source[$offset]);
    }


}
