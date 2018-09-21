<?php
namespace PhpApi\Standard\Request\Pool;

/**
 * HttpClient客户端信息 
 * 注意每个类对接口RequestInterface的方法，虽然这个接口没有类Impl去实现
 *  
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class HttpClientInfo extends RequestDataBase {
    /**
     * @var currentKey 在池中的键名key
     */
    protected $currentKey = 'HttpClientInfo';
    /**
     * 当前在调用的request顶级类
     */
    protected $requestObj = null;

    /**
     * @var 获取的数据
     * 严格按照接口来分类
     * USER_AGENT
     * REMOTE_PORT
     * REMOTE_ADDR
     */
    protected $requestData = array(
        'HTTP_USER_AGENT' => '',
        'REMOTE_PORT' => '',
        'REMOTE_ADDR' => ''
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