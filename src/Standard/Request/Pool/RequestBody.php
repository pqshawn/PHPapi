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
     * 当前在调用的request顶级类
     */
    protected $requestObj = null;

    protected function __construct() {

    }
    /**
     * 处理数据
     */
    public function make() {
        // 有没有被别类已经运算参数
        if (empty(self::$bodyData)) {
            $this->parseBodyData();
        }
        return self::$bodyData;
    }


    /**
     * 反射数据
     */
    public function reflecteData(Controller $controller, $resData = []) {
        if (!empty($resData)) {
            if (isset($controller::$requestData[__METHOD__])) return;

            $controller::$requestData[__METHOD__] = $resData;
        }
    }





}