<?php
namespace PhpApi\Standard\Logger;

/**
 * 日志类
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

 interface LoggerInterface {

    /**
     * 观察方法
     * 可由其他类触发，为其他类写日志
     */
    public function onChanged();

    /**
     * read
     */
    public function read();

    /**
     * write
     */
    public function write();




 }