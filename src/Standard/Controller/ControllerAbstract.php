<?php
namespace PhpApi\Standard\Controller;

/**
 * controller抽象类
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

 abstract class ControllerAbstract implements ControllerInterface{

    /**
     * 前置方法
     */
    abstract public function beforeAction();

    /**
     * 后置方法
     */
    abstract public function afterAction();
 }
