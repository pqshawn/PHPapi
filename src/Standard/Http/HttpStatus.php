<?php
namespace PhpApi\Standard\Http;

/**
 * 
 * Http状态码
 * 该类有助于我们构建PHPapi自己的状态码
 * 
 * 1xx 信息状态码
 * 2xx 成功状态码
 * 3xx 重定向状态码
 * 4xx 客户端错误状态码
 * 5xx 服务端错误状态码
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 class HttpStatus {
     protected $httpStatus = 
     array(
         '200' => array('message' => 'OK'),
         '204' => array('message' => 'No Content'),
         '206' => array('message' => 'Partial Content'),

         '301' => array('message' => 'Moved Permanently'),
         '302' => array('message' => 'Found'),// 临时移动,弱化禁止POST换成GET
         '303' => array('message' => 'See Other'),// 与302不同点，是明确GET方法
         '304' => array('message' => 'Not Motified'), // GET请求报文里IF-Match等，给访问，但不含主体
         '307' => array('message' => 'Temporary Redirect'), // 临时移动,禁止POST换成GET

         '400' => array('message' => 'Bad Request'),
         '401' => array('message' => 'Unauthorized'), // basice等认证
         '403' => array('message' => 'Forbidden'),// 权限
         '404' => array('message' => 'Not Found'),

         '500' => array('message' => 'Internal Server Error'), // web应用bug,或故障
         '503' => array('message' => 'Service Unavailable'), // 超负载或停机维护，写retryAfter首部
     );

 }