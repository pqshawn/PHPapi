<?php
namespace PhpApi\Standard\Request\Pool;

/**
 * body-request 
 *  
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class RequestBody extends RequestDataBase {
    /**
     * @var currentKey 在池中的键名key
     */
    protected $currentKey = 'RequestBody';
    /**
     * 当前在调用的request顶级类
     */
    protected $requestObj = null;

    /**
     * @var 获取的数据
     */
    protected $requestData = '';

    public function __construct() {

    }
    /**
     * 处理数据
     */
    public function make() {
        // 有没有被别类已经运算参数
        if (empty(parent::$bodyData)) {
            $this->parseBodyData();
        }
        $this->requestData = parent::$bodyData;
        return $this->requestData;
    }






}