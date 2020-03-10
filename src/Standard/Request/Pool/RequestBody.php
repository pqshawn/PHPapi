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
     * 处理数据
     */
    public function make() {
        // 有没有被别类已经运算参数
        if (empty(self::$bodyData)) {
            $this->parseBodyData();
        }

        $body = self::$bodyData;

        



    }





}