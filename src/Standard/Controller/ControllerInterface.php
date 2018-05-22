<?php
namespace PhpApi\Standard\Controller;

/**
 * controller接口，规范
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 interface ControllerInterface {

    /**
     * 前置方法
     */
    function beforeAction();

    /**
     * 后置方法
     */
    function AfterAction();
 }

