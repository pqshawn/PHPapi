<?php
namespace PhpApi\Tests;

use PHPUnit\Framework\TestCase;
use PhpApi\Kernel;
/**
 * 主启动类
 * kernel启动
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

class KernelTest extends TestCase {

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

    }

    /**
     * @group request
     * test requestHeader
     */
    public function testRequestHeader() {

    }

    /**
     * @group request
     * test requestBody
     */
    public function testRequestBody() {

    }

    /**
     * @group request
     * test requestBody
     */
    public function testRequest() {
        $request = \PhpApi\Di::single()->request;
        // fwrite(STDOUT,print_R($request, true). "\n");

    }


    /**
     * test badRequest
     */
    public function testBadRequest() {
        
    }

    /**
     * 测试response工厂
     */
    public function testResponse() {
        $response = \PhpApi\Di::single()->response->get_body();
        $this->expectOutputRegex('/"ret":200/');
    }

    
}
