<?php
namespace PhpApi\Standard\Router;

/**
 * Router 规范接口
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

interface RouterInterface {
    /**
     * 解析路由
     */
    public function parse();

    /**
     * 路由转向
     */
    public function dispatch();
}
 