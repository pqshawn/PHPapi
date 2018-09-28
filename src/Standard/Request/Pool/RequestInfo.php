<?php
namespace PhpApi\Standard\Request\Pool;

/**
 * 基本信息-request 
 * 
 *
 * 访问的相关信息
 * CONTENT_TYPE
 * REQUEST_URI
 * REQUEST_METHOD
 * REQUEST_TIME
 * PATH_INFO
 * DOCUMENT_ROOT
 * QUERY_STRING 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class RequestInfo extends RequestDataBase {
    /**
     * @var currentKey 在池中的键名key
     */
    protected $currentKey = 'RequestInfo';
    /**
     * 当前在调用的request顶级类
     */
    protected $requestObj = null;

    /**
     * @var 获取的数据,在RequestDataBase里的命名
     */
    protected $requestData = array(
        'CONTENT_TYPE'   => '',
        'REQUEST_URI'    => '',
        'REQUEST_METHOD' => '',
        'REQUEST_TIME'   => '',
        'PATH_INFO'      => '',
        'QUERY_STRING'   => '',
    );
    

    public function __construct() {

    }
    /**
     * 处理数据
     */
    public function make() {
        // 有没有被别类已经运算参数
        if (empty(parent::$serverRequest)) {
            $this->parseServerRequest();
        }
        if (!empty(parent::$serverRequest)) {
            foreach ($this->requestData as $key => &$dataVal) {
                $dataVal = isset(parent::$serverRequest[$key])? parent::$serverRequest[$key] : '';
            }
        }
        return $this->requestData;
    }







}