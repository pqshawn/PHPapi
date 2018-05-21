<?php
namespace PhpApi;

use PhpApi\Standard\DependenceIn;

/**
 * 依赖注入类
 * dependence injection class
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

class Di extends DependenceIn {

    /**
	 * @param object $instance 
	 */
    protected static $instance = NULL;

    // 预设两个变量，请求和响应
    /**
	 * @param array $request
	 */
    protected $request = [];
    
    /**
     * @param array $response
     */
    protected $response = [];

    public function __construct() {
        
    }

    /**
	 * 单例方法
	 */
    public static function single() {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


}
