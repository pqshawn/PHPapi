<?php
namespace PhpApi\Standard\Router;
/**
 * normal router
 * 
 * 暂时不固化，所有路由，用Auto类
 * for example: http[s]://xxx.xx/App/v1/Site/Add
 * when you choose normal router or no choose
 *
 * @author:Yzwu <Ldos.net>
 */

class PathinfoRouter extends RouterAbstract {  
    
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