<?php
namespace PhpApi\Standard\Security;

/**
 * 
 * Http安全抽象类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

abstract class HttpSecurityAbstract {
    
    /**
     * sniffer
    * PacketCapture，Sniffer,ettercap
    * Wireshark,findler...etc
    */
    abstract protected function sniffer();
    
    /**
     * certificate
     * 验明来意和身份
     */
    abstract protected function certificate();

    /**
     * 验证报文完整性和真实性
     */
    abstract protected function checkMitmAttack();

    /**
     * Http1.0认证方式 - BASIC 认证
     * 
     */
    abstract protected function userBasic();

    /**
     * Http1.1认证方式 - DIGEST 认证
     * 不防改
     */
    abstract protected function userDigest();

    /**
     * Http1.1认证方式 - SSL 客户端认证
     */
    abstract protected function userSslClient();

    /**
     * Http1.1认证方式 - 基于表单认证FormBase
     */
    protected function userFormBase() {
        $userSecurityPost = 'https';
        $userSecruitySession = 'expire-valid-generate';
        $userSecurityCookie = 'httponly';

        // 防彩虹表破解，加盐
        $userSecruityPasswd = 'hash(salt+passwd)';
    }


      
}