<?php
namespace PhpApi\Standard\Security;

/**
 * 
 * web攻击抽像类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

abstract class WebAttack {
    protected $way = [0 => '主动攻击', 1 => '被动攻击'];

    /**
     * client server 输入值验输出值转义
     */
    protected function quote() {

    }

    /**
     * xss
     */
    protected function xss() {

    }

    /**
     * sql inject ' " '--' 1=1 =true show delete...
     */
    protected function sqlInject() {

    }

    /**
     * OS 
     */
    protected function commandInject() {

    }

    /**
     * Http header inject 
     */
    protected function httpHeaderInject() {

    }
    
    /**
     * Directory
     */
    protected function directory() {

    }
    
    /**
     * Remote File
     */
    protected function remoteFile() {

    }

    /**
     * 设计上的暴露，如没有权限页面的静态资源
     */
    protected function designer() {

    }

    /**
     * 重定向 xxx？redirect=http://hacker.com
     */
    protected function redirect() {

    }

    /**
     * Session Hijack
     */
    protected function sessionHijack() {

    }

    /**
     * 会话固定攻击Session Fixation
     */
    protected function sessionFixation() {

    }

    /**
     * 跨站点请求伪造CSRF,如布陷图片，引访问目标网站，获取cookie等
     */
    protected function csrf() {
        
    }

    /**
     * Clickjacking
     */
    protected function clickjacking() {
        
    }

    /**
     * DoS , 攻防：分布\熔断机制、服务降级、服务限流、解决服务雪崩效应
     */
    protected function dos() {
        
    }
    
    /**
     * Backdoor
     */
    protected function backDoor() {
        
    }

    /**
     * passwd
     */
    protected function passwd() {
        
    }



    

    
    
}