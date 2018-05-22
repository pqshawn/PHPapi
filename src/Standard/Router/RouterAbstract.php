<?php
namespace PhpApi\Standard\Router;

/**
 * Router 规范抽象类，不可实例化
 * 提供router普通通用属性方法
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

abstract class RouterAbstract implements RouterInterface{

    /**
     * 提供一个正常可以解析的mapper
     * 
     * @var mapper array{
     * 'namespace' => '',
     * 'controller' => '',
     * 'action' => '',
     * 'getParam' => '',
     * }
     */
    protected $mapper = [
        'namespace' => 'App',
        'controller' => 'Test',
        'action' => 'get',
        'getParam' => '',
        'mode' => 'Auto',
    ];


    /**
     * 解析路由
     * 解析出controller action  如果有param，加上param
     * view-source:http://192.168.1.37/app/v1/news/add?gewew=gew3
     * view-source:http://192.168.1.37/app.v1.news.dw.dsw?gewew=gew3
     * 
     * @param void
     * @return void
     */
    protected function parse() {
        $match = array(); // 捕获1
        $mapperRes = '';
        // 不管是dot还是pathinfo统一解析出相应参数
        $noIndexUri = str_replace('/index.php', '', $_SERVER['REQUEST_URI']);
        $matchRes = preg_match('/([\.\/a-zA-Z0-9\-\_]+)\\?{0,1}/', $noIndexUri, $match);

        $mapperArray = [];
        if ($matchRes !== false && isset($match[1])) {
            $mapperRes = ltrim($match[1], '/');
            $mapperArray = preg_split("/[\.\/]/", $mapperRes);
            // ucfirst 
            array_walk($mapperArray, $this->routerUcfirst);
        }

        // get namespace and action
        if(count($mapperArray) > 2) {
            $this->mapper['namespace'] = array_unshift($mapperArray);
            $this->mapper['action'] =array_pop($mapperArray);
        }
        // controller
        $this->mapper['namespace'] = $this->mapper['namespace'] . implode('\\', $mapperArray);
        // getParam
        $this->mapper['getParam'] = isset($_SERVER['QUERY_STRING'])? $_SERVER['QUERY_STRING'] : '';
        // mode
        $this->mapper['mode'] = $this->mode();
    }

    /**
     * 模式
     * 调用的子类实现此方法
     */
    abstract protected function mode();
    
    protected function routerUcfirst(&$item, $key)
    {
        return ucfirst($item);
    }

    /**
     * 路由转向
     */
	abstract protected function dispatch();
	
	
	
}
 