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
     * @var 当前调用的子类标识key
     */
    protected $currentKey = 'Default';

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
    public function reflecteData(\PhpApi\Controller $controller) {
        if (!empty($this->requestData)) {
            $className = $this->currentKey;
            if (isset($controller::$requestData[$className])) return false;

            $controller::$requestData[$className] = $this->requestData;
        }
    }


    /**
     * 解析数据池的主要来源
     */
    final protected function parseServerRequest() {
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
    final protected function parseBodyData() {
        if(!empty(self::$bodyData)) {
            return self::$bodyData;
        }
        isset($_SERVER['CONTENT_TYPE'])? $this->contentType = $_SERVER['CONTENT_TYPE'] : '';
        
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
    final protected function parseQueryString() {
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], self::$queryStrToArray);
        }
    }

    /**
     * 解密适配
     */
    final protected function decrypt($cryptObj = null, $res = '') {
        // 注意加密解密的iv可能会变动，客户端如果headers里传Authentication，则用此当iv

        return $cryptObj->comDecrypt($res);
    }

    final protected function uncompress($type = 'ZLIB', $res = '') {
        $result = $res;
        if ($type == 'ZLIB') {
            $result = gzuncompress($res);
        }
        return $result;
    }



}