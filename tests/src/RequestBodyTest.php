<?php
namespace PhpApi\Tests;

use PhpApi\Standard\Request\Pool\RequestBody;

/**
 * Request Test
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class RequestBodyTest extends \PHPUnit\Framework\TestCase {

    protected static $obj = null;
    
    public static function setUpBeforeClass()
    {
        self::$obj = new RequestBody();
    }

    public function setUp() {
    }

    public function testEmpty()
    {
        $this->assertTrue(!empty(self::$obj));
    }

    public function tearDown() {
    }

    public static function tearDownAfterClass()
    {
        self::$obj = null;
    }

    /**
     * 测试实例化
     */
    public function testObject() {
        $this->assertInstanceOf(RequestBody::class, self::$obj);
    }

    /**
     * 测试实例化
     */
    public function testTrueObject() {
        $n = new \PhpApi\Standard\Request\Pool\RequestCommonHeader();
        $n->make();
        self::$obj->make();
        
        // $stub = $this->createMock(\PhpApi\Model\ModelFactory::class);

        // // 配置桩件。
        // $stub->method('router')
        //      ->will($this->returnSelf());

        // $this->assertSame($stub, self::$model);
    }

}