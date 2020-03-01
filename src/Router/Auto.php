<?php
namespace PhpApi\Router;

use PhpApi\Standard\Router\RouterAbstract;

/**
 * normal router
 * for example: http[s]://xxx.xx/V1.Site.Add
 * when you choose normal router or no choose
 *
 * @author:Yzwu <Ldos.net>
 */

class Auto extends RouterAbstract {
    
    /**
     * 模式
     */
    protected function mode() {
        return __METHOD__;
    }

    /**
     * 解析路由
     */
    public function parse() {
        return parent::parse();
    }

    
    /**
     * 路由转向
     * 
     * @param void
     * @return void
     */
	public function dispatch() {
        // 解析到类变量
        $this->parse();
        // 路由转向
        $controllerName = '\\' . str_replace('/', '\\', $this->mapper['namespace'] . '\\' . $this->mapper['controller']);
        $actionName = $this->mapper['action'];

        $controller = new $controllerName();
        if (!method_exists($controller, $actionName) || !is_callable(array($controller, $actionName))) {
            // 异常处理
        } else {
            $controller->$actionName();
        }
	}
}