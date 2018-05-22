<?php
namespace PhpApi\Standard\Request;

/**
 * requst 规范化基类
 * reques the data from net 
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Shawn Yu <pggq@outlook.com>
 */

interface RequestInterface {
    
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
    public function getRequestHeader();

    /**
     * 获取通用首部字段
     * 
     * CACHE_CONTROL
     * Connection
     * 
     */
    public function getCommonHeader();


     /**
      * 请求行
      *
      * REDIRECT_STATUS
      */
    public function getRequestLine();


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

    public function getHttpWebInfo();
    

    /**
    * client信息
    *
    * REMOTE_PORT
    * REMOTE_ADDR
    */
    public function getClientInfo();

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
    public function getRequestInfo();

    /**
     * COOKIE
     */
    public function getRequestCookie();
    

    /**
     * 获取主体
     */
    public function getBody();



}


