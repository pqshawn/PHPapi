<?php
namespace PhpApi\Standard\Request\Pool;

/**
 * 通用头部 
 * 如果没有正常说明的，都默认经过web服务器处理，被cgi加工过的
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class RequestCommonHeader extends RequestDataBase {
    
    /**
     * 处理数据
     */
    public function make() {
        // 有没有被别类已经运算基类
        if (empty(self::$serverRequest)) {
            $this->init();
        }

        print_R(self::$serverRequest);



    }





}