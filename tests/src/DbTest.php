<?php
namespace PhpApi\Tests;

use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use PHPUnit\Extensions\Database\DataSet\ArrayDataSet;

class DbTest extends \PHPUnit\Framework\TestCase
{
    use TestCaseTrait;

    /**
    * 以数组格式建立数据库基境 
    */
    protected function getDateSet()
    {
        return new ArrayDataSet($this->getInitDataSet());
    }

    /**
    * 返回PDO对象
    */
    protected function getConnection()
    {
        $pdo;

        return $this->createDefaultDBConnection($pdo, 'schemaName');
    }

    /**
    * 测试方法
    */
    public function testGetUserById()
    {
        $user = new User();
        $id = $this->getInitDataSet()['user'][0]['id'];
        $result = $user->getUserById($id);

        $this->assertEquel($this->getUserByIdDataSet(), $result);
    }

    /**
    * 数据库的初始化数据, 即每次测试之前, 数据库里的数据集就是该基境数据
    */
    private function getInitDataSet()
    {
        return [
            'user' => [
                [
                   'id' => 1,
                   'name' => 'joy',
                ]
            ],
        ];
    }

    /**
    * 与通过模型层查询出来的数据进行对比
    */
    private function getUserByIdDataSet()
    {
        return $this->getInitDataSet()['user'][0];
    }
}