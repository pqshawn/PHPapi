<?php
namespace PhpApi\Standard\Router;
/**
 * normal router
 * for example: http[s]://xxx.xx/V1.Site.Add
 * when you choose normal router or no choose
 *
 * @author:Yzwu <Ldos.net>
 */

class Auto extends RouterAbstract {

	protected $mapper = [];
    
    /**
     * 模式
     */
    protected function mode() {
        return __METHOD__;
    }

    /**
     * 路由转向
     */
	protected function dispatch() {
		
	}
}