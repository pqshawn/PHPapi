<?php
namespace PhpApi\Standard\Request;

/**
 * requst 数据池基类
 * 这里主要获取http的数据，其实已经过webserver和cgi的处理，获取到加工好的http数据
 * 统一获取，只所以还要分类，是因为这类数据不止是业务在用，运维，产品都可能运用到此类数据，先归好类（当然这可以根据业务定制，只有原始数据可以返回的）
 * 分类后的数据，有两个去向，一是反射给业务api类作response处理，二是可反射到运维平台，日志平台等等（开发需要自己配置，可选择关闭）。
 *  
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

 class RequestPoolParse implements \Iterator {

    /**
     * 键
     */
    protected $key = 0;
    /**
     * @var 各种类型请求对象集合
     */
    protected $requestDataObjs = array();  
    /**
     * 把最终值反射到的类，如request工厂类直接需要，就无需再一层调用request车间
     */
    protected $reflectObj = null;

    /**
     * params是个未知个数的obj组,这个据顶级类的需要而传参过来
     * 顶级类传参一批obj组，obj然后通过反射
     */
    public function __construct(\PhpApi\Controller $controller) {
        $this->reflectObj = $controller;
    }

    /**
     * 添加数据类型
     */
    public function addRequestType($requestDataTypeNames = array()) {
        if (is_array($requestDataTypeNames)) {
            $Di = \PhpApi\Di::single();
            foreach ($requestDataTypeNames as $name) {
                $this->requestDataObjs[] = $Di->$name;
            }
        }
    }

    public function rewind() {
        $this->key = 0;
    }

    public function current() {
        $currenStr = $this->requestDataObjs[$this->key];
        $currenObj = new $currenStr;
        $resData = $currenObj->make();
        $currenObj->reflecteData($this->reflectObj);
        return true;
    }

    public function key() {
        return $this->key;
    }

    public function next() {
        ++$this->key;
    }

    /**
     * valid
     * 
     * param void
     * return boolean
     */
    public function valid() {
        if (!isset($this->requestDataObjs[$this->key])) return false;
        return class_exists(get_class($this->requestDataObjs[$this->key]));
    }

}