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
        'namespace' => 'App/V1/Api',
        'controller' => 'Test',
        'action' => 'get',
    ];
    // 当前路由的模式
    protected $currentMode = '';

    /**
     * 解析路由
     * 解析出controller action  如果有param，加上param
     * view-source:http://192.168.1.37/app/v1/News/add?gewew=gew3
     * view-source:http://192.168.1.37/app.v1.News.Dw.dsw?gewew=gew3
     * 
     * @param void
     * @return void
     */
    public function parse() {
        $match = array(); // 捕获1
        $mapperRes = '';
        // 不管是dot还是pathinfo统一解析出相应参数
        if (isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/') {
            $requestUri = $_SERVER['REQUEST_URI'];
        } else {
            return $this->mapper;
        }
        $noIndexUri = str_replace('/index.php', '', $requestUri);
        $matchRes = preg_match('/([\.\/a-zA-Z0-9\-\_]+)\\?{0,1}/', $noIndexUri, $match);
        $mapperArray = [];
        if ($matchRes !== false && isset($match[1])) {
            $mapperRes = ltrim($match[1], '/');
            $mapperArray = preg_split("/[\.\/]/", $mapperRes);
            array_walk($mapperArray, array($this, 'routerUcfirst'));
        }
        if(count($mapperArray) > 2) {
            $this->mapper['namespace'] = array_unshift($mapperArray);
            $this->mapper['action'] =array_pop($mapperArray);
        }
        // controller
        $this->mapper['controller'] = $this->mapper['namespace'] . implode('\\', $mapperArray);
        $this->mapper['getParam'] = isset($_SERVER['QUERY_STRING'])? $_SERVER['QUERY_STRING'] : '';
        $this->currentMode = $this->mode();
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
	abstract public function dispatch();
	
	
	
}
 