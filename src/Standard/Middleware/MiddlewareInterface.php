<?php
namespace PhpApi\Standard\MiddleWare;

/**
 * 中间件interface
 * 中间件是使用了观察模式，为了降低耦合，与其他观察类解构
 * 
 * 别类调用中间件，中间件触发属于自己的任务，处理结果原路返回，或者开启观察，
 * 让观察者处理后续任务
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

 interface MiddleWareInterface {

    /**
     * 中间件触发某任务
     */
    public function middleTask();

    /**
     * 增加观察
     * 解发的任务最后结果也通知观察（如，日志类）
     */
    public function addObserver();




 }