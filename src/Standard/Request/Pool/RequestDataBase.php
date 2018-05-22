<?php
namespace PhpApi\Standard\Request\Pool;

/**
 * requst 各数据的抽象基类
 * 
 * 分类后的数据，有两个去向，一是反射给业务api类作response处理，二是可反射到运维平台，日志平台等等（开发需要自己配置，可选择关闭）。
 *  
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

abstract class RequestDataBase {

    /**
     * @var 池数据的主要来源
     */
    protected $serverRequest = [];

    /**
     * @var 主体数据
     * 可能会有加密解密，根据content-type判断类型执行不同操作
     */
    protected $bodyData = [];

    /**
     * @var QUERY_STRING的解析
     * 主要针对两种数据：1.REQUEST_METHOD为get类型,把get的数据拼接进url
     * 2.针对url添加的类型参数，如post请求，但时间戳，分页参数放进url里
     */
    protected $queryStrToArray = [];

    /**
     * @var string contentType
     */
    protected $contentType = 'application/json';
    

    public function __construct() {

    }
    /**
     * 处理数据
     */
    abstract protected function make();

    /**
     * 反射数据
     */
    protected function reflecteData() {

    }


    /**
     * 解析数据池的主要来源
     */
    final function parseServerRequest() {

    }

    /**
     * 解析body
     * 
     * @return array $dataRes
     */
    final function parseBodyData() {
        if ($this->contentType == 'application/json') {
            // get , post 
            $dataRes = $_REQUEST;
        } else {
            $data = file_get_contents('php://input');
            $dataRes = json_decode($data, true);
        }
        return $dataRes;
    }


}