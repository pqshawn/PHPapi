<?php
namespace PhpApi\Tests;

use PhpApi\Model;

/**
 * Model Test
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class ModelTest extends \PHPUnit\Framework\TestCase {

    protected static $model = null;
    protected static $configDb = [];
    
    public static function setUpBeforeClass()
    {
        // 加载配置
        $configDb = \PhpApi\Di::single()->config->config['App']['Db']['Master'];
        // 自己命名table表，不根据类名自动生成
        $useMyTableName = true;
        self::$model = new Model($useMyTableName, $configDb);
    }

    public function setUp() {
    }

    public function testEmpty()
    {
        $this->assertTrue(!empty(self::$model));
    }

    public function tearDown() {
    }

    public static function tearDownAfterClass()
    {
        self::$model = null;
    }



    /**
     * 测试实例化
     */
    public function testObject() {
        $this->assertInstanceOf(Model::class, self::$model);
    }

    /**
     * 测试表
     * 库名：test 表名："test"
     */
    /*
    create database test  default character set utf8 collate utf8_general_ci;
    
    CREATE TABLE `test` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(45) NOT NULL,
    `content` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8
    */
    public function testTable() {
        // 测试test表
        self::$model->_table = 'test';
        $data = array(
            'title'   => 'second title',
            'content' => 'second content'
        );
        $result = self::$model->create($data);
        $ret = isset($result['rs'])? $result['rs'] : false;
        $this->assertTrue($ret);
        // $this->assertGreaterThan(-1, $result);
    }

    /**
     * 测试实例化
     */
    public function testTrueObject() {
        
        
        $stub = $this->createMock(\PhpApi\Model\ModelFactory::class);

        // 配置桩件。
        $stub->method('router')
             ->will($this->returnSelf());

        $this->assertSame($stub, self::$model);
    }

    



    
}
