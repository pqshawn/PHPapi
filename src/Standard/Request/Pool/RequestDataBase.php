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
    protected static $serverRequest = [];

    /**
     * @var 主体数据
     * 可能会有加密解密，根据content-type判断类型执行不同操作
     */
    protected static $bodyData = [];

    /**
     * @var QUERY_STRING的解析
     * 主要针对两种数据：1.REQUEST_METHOD为get类型,把get的数据拼接进url
     * 2.针对url添加的类型参数，如post请求，但时间戳，分页参数放进url里
     */
    protected static $queryStrToArray = [];

    /**
     * @var string contentType 默认是标准json
     */
    protected $contentType = 'application/json';
    // config @todo
    protected $cryptType = 'AesCrypto';
    protected $compressType = 'ZLIB';
    

    protected function __construct() {

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
        if(!empty(self::$serverRequest)) {
            return self::$serverRequest;
        }
        self::$serverRequest = $_SERVER;
        return $_SERVER;
    }

    /**
     * 解析body
     * 
     * @return void
     */
    final function parseBodyData() {
        if(!empty(self::$bodyData)) {
            return self::$bodyData;
        }

        if ($this->contentType == 'application/json') {
            // get , post 传来的
            $dataRes = $_REQUEST;
        } else {
            // 解析数据
            $data = file_get_contents('php://input');
            // 解密
            $cryptType = $this->cryptType;
            $cryptClassName = '\\PhpApi\\Crypto\\' . $cryptType;
            $cryptObj = new $cryptClassName();
            $dataRes = $this->decrypt($cryptObj, $data);
        }
        self::$bodyData = $dataRes;
        return $dataRes;
    }

    /**
     * QUERY_STRING
     */
    final function parseQueryString() {
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], self::$queryStrToArray);
        }
    }

    /**
     * 解密适配
     */
    final function decrypt($cryptObj = null, $res = '') {
        return $cryptObj->comDecrypt($res);
    }

    final function uncompress($type = 'ZLIB', $res = '') {
        $result = $res;
        if ($type == 'ZLIB') {
            $result = gzuncompress($res);
        }
        return $result;
    }



}