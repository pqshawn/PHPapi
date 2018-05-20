<?php
namespace PhpApi\Tests;

use PhpApi\Kernel;

/**
 * 主启动类
 * kernel启动
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

class KernelTest extends \PHPUnit\Framework\TestCase {

    protected $kernel = null;
    
    public function setUp() {
        $this->kernel = new Kernel();
    }

    public function testEmpty()
    {
        $this->assertTrue(!empty($this->kernel));
    }

    public function tearDown() {
    }


    /**
     * 主启动类的单例
     */
    public function testSingle() {
        $this->assertInstanceOf(Kernel::class, $this->kernel::single(Kernel::class, ''));
    }

    /**
     * @group request
     * STDIN
     */
    public function testStdin() {
        $this->markTestIncomplete(
                'STDIN CLi脚本时再测@todo'
                );
    }

     /**
     * STDOUT
     */
    public function testStdout() {
        $this->markTestIncomplete(
            __METHOD__.'@todo'
            );
    }

    /**
     * @group request
     * test requestHeader
     */
    public function testRequestHeader() {
        $this->markTestIncomplete(
            __METHOD__.'@todo'
            );
    }

    /**
     * @group request
     * test requestBody
     */
    public function testRequestBody() {
        $this->markTestIncomplete(
            __METHOD__.'@todo'
            );
    }

    /**
     * @group request
     * test requestBody
     */
    public function testRequest() {
        $request = \PhpApi\Di::single()->request;
        $this->expectOutputRegex('/"ret":200/');
        // fwrite(STDOUT,print_R($request, true). "\n");

    }



    /**
     * @group router
     * 
     * test router默认路径测试app.v1.Test->get
     */
    public function testRouter() {
        $router = \PhpApi\Di::single()->router;
        $routerInstance = $router->load();
        $parseUrl = null;
        $parseHasKey = 'controller';
        $className = '\\PhpApi\\Router\\'.$router->routeMode;
        $subSet = array('title' => 'test1');

        // 判断类对象的建立
        $this->assertInstanceOf($className, $routerInstance);

        // 解析路由
        $parseUrl = $routerInstance->parse();
        $this->assertArrayHasKey($parseHasKey, $parseUrl);
        
        // dispatch
        $ret = $routerInstance->dispatch(true);
        $this->assertArraySubset($subSet, $ret);
    }





    /**
     * test badRequest
     */
    public function testBadRequest() {
        $this->markTestIncomplete(
            __METHOD__.'@todo'
            );
    }

    /**
     * 测试response工厂
     */
    public function testResponse() {
        $response = \PhpApi\Di::single()->response->getBody();
        $this->expectOutputRegex('/"ret":200/');
    }

    
}
