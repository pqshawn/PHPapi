<?php
namespace PhpApi\Tests;

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    protected $stack;

    protected function setUp()
    {
        $this->stack = [];
    }
    public function testEmpty()
    {
        $this->assertTrue(empty($this->stack));
    }

    public function testPush()
    {
        array_push($this->stack, 'foo');
        $this->assertEquals('foo', $this->stack[count($this->stack)-1]);
        $this->assertFalse(empty($this->stack));
    }

    public function testPop()
    {
        array_push($this->stack, 'foo');
        $this->assertEquals('foo', array_pop($this->stack));
        $this->assertTrue(empty($this->stack));
    }

    /**
     * @dataProvider additionProvider
     */
    public function testAdd($a, $b, $expected)
    {
        $this->assertEquals($expected, $a + $b);
    }

    public function additionProvider()
    {
        return [
            'adding zeros'  => [0, 0, 0],
            'zero plus one' => [0, 1, 1],
            'one plus zero' => [1, 0, 1],
            'one plus one'  => [1, 1, 3]
        ];
    }
    public function testExpectBarActualBaz()
    {
        $this->expectOutputString('bar');
        print 'baz';
    }
    // public static function setUpBeforeClass()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    // }

    // protected function setUp()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    // }

    // protected function assertPreConditions()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    // }

    // public function testOne()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    //     $this->assertTrue(true);
    // }

    // public function testTwo()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    //     $this->assertTrue(false);
    // }

    // protected function assertPostConditions()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    // }

    // protected function tearDown()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    // }

    // public static function tearDownAfterClass()
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    // }

    // protected function onNotSuccessfulTest(Exception $e)
    // {
    //     fwrite(STDOUT, __METHOD__ . "\n");
    //     throw $e;
    // }
    /**
     * 对private方法反射测试
     */
    public function testSample()
    {
        // $method = new ReflectionMethod('Sample', 'run');
        // $method->setAccessible(true);
        // $method->invoke(new Sample());
    }


    public function testReturnValueMapStub()
    {
        // 为 SomeClass 类创建桩件。
        $stub = $this->createMock(SampleClass::class);

        // 创建从参数到返回值的映射。
        $map = [
            ['ab', 'b', 'c', 'd'],
            ['创建', 'f', 'g', 'h']
        ];

        // 配置桩件。
        $stub->method('doSomething')
             ->will($this->returnValueMap($map));

        // $stub->doSomething() 根据提供的参数返回不同的值。
        $this->assertEquals('d', $stub->doSomething('ab', 'b', 'c'));
        $this->assertEquals('h', $stub->doSomething('创建', 'f', 'g'));
    }
}
class SampleClass {
    public function doSomething() {

    }
}
