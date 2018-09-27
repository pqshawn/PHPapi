<?php
namespace PhpApi\Standard\Request;

/**
 * requst 抽象化基类
 * reques the data from pool
 * pool用反射得到
 * 每个属性都可能对应一个pool里的类，可以在池子里添加更多不同的类别，然后通过反射，把各类的数据反射到RequestAbstract里
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

abstract class RequestAbstract implements RequestInterface {
    
    protected $requestHeader = [];

    protected $commonHeader = [];

    protected $requestLine = [];

    /**
     * @var 服务器信息
     */
    protected $httpWebInfo = [];

    /**
     * @var 客户端信息
     */
    protected $clientInfo = [];

    /**
     * @var request重要信息（webSer,cgi加工过）
     */
    protected $requestInfo = [];

    /**
     * @var cookie信息
     */
    protected $requestCookie = [];

    /**
     * @var getBody
     */
    protected $body = [];



    /**
     * 获取请求首部字段
     * 
     * Accept-Language
     * Accept-Encoding
     * Accept
     * USER_AGENT
     * Host
     * 
     */
    protected function getRequestHeader() {

    }

    protected function setRequestHeader() {

    }

    /**
     * 获取通用首部字段
     * 
     * CACHE_CONTROL
     * Connection
     * 
     */
    protected function getCommonHeader() {

    }

    protected function setCommonHeader() {

    }


     /**
      * 请求行
      *
      * REDIRECT_STATUS
      */
    protected function getRequestLine() {

    }

    protected function setRequestLine() {

    }


    /**
     * httpweb信息
     * 
     * SERVER_NAME
     * SERVER_PORT
     * SERVER_ADDR
     * SERVER_SOFTWARE
     * GATEWAY_INTERFACE
     * SERVER_PROTOCOL
     */

    public function getHttpWebInfo() {

    }

    public function setHttpWebInfo() {

    }
    

    /**
    * client信息
    *
    * REMOTE_PORT
    * REMOTE_ADDR
    */
    public function getClientInfo() {

    }

    public function setClientInfo() {

    }

    /**
     * 访问的相关信息
     * 
     * CONTENT_TYPE
     * REQUEST_URI
     * REQUEST_METHOD
     * REQUEST_TIME
     * PATH_INFO
     * DOCUMENT_ROOT
     * QUERY_STRING
     */
    public function getRequestInfo() {

    }

    public function setRequestInfo() {

    }

    /**
     * COOKIE
     */
    public function getRequestCookie() {

    }

    public function setRequestCookie() {

    }
    

    /**
     * 获取主体
     */
    public function getBody() {

    }

    public function setBody() {

    }



}


