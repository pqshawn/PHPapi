<?php
namespace PhpApi\Standard\Request\Pool;

/**
 * requst 数据池基类
 * 这里主要获取http的数据，其实已经过webserver和cgi的处理，获取到加工好的http数据
 * 统一获取，只所以还要分类，是因为这类数据不止是业务在用，运维，产品都可能运用到此类数据，先归好类（当然这可以根据业务定制，只有原始数据可以返回的）
 * 分类后的数据，有两个去向，一是反射给业务api类作response处理，二是可反射到运维平台，日志平台等等（开发需要自己配置，可选择关闭）。
 *  
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

final class PoolParse implements Iterator {

    /**
     * 键
     */
    protected $key = 0;
    /**
     * @var 各种类型请求对象集合
     */
    protected $requestDataObjs = array();  

    protected function __construct() {
        // 不读文件，直接手动配置
        $$this->requestDataObjs = array(
            new Body(),
            new ClientInfo(),
            new CommonHeader(),
            new HttpWebInfo(),
            new RequestCookie(),
            new RequestHeader(),
            new RequestInfo(),
            new RequestLine()
        );
    }

    public function rewind() {
        $this->key = 0;
    }

    public function current() {
        $currenObj = $this->requestDataObjs[$this->key];
        $currenObj->make();
        $currenObj->reflecteData();
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
        return class_exists($this->requestDataObjs[$this->key]);
    }

}