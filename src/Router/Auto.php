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
        // 项目自定义默认路由, 验证@todo
        if (!isset($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/') {
            $configObj = \PhpApi\Di::single()->config;
            $config = $configObj->config;
            $appName = $configObj->appName;
            
            if (isset($config[$appName]['Default']) && md5(json_encode(array_keys($config[$appName]['Default']))) == '1c4c4d78a945d9cd8496d7b214c4ec03') {
                $this->mapper = $config[$appName]['Default'];
                return $this->mapper;
            }
        }

        return parent::parse();
    }

    
    /**
     * 路由转向
     * 
     * @param void
     * @return void
     */
	public function dispatch() {
        $data = [];
        // 解析到类变量
        $this->parse();
        // 路由转向
        $controllerName = '\\' . str_replace('/', '\\', $this->mapper['controller']);
        $actionName = $this->mapper['action'];

        $controller = new $controllerName();
        if (!method_exists($controller, $actionName) || !is_callable(array($controller, $actionName))) {
            // 异常处理
        } else {
            // 前置方法

            $data = $controller->$actionName();
            
            // 后置方法
        }
        // 输出
        $controller->output($data);
	}
}