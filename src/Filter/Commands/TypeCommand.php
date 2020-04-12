<?php
namespace PhpApi\Filter\Commands;

use PhpApi\Standard\Filter\FilterAbstract;

/**
 * Filter Command----Type
 * 判断type类型，json的基础类型有int,string,bool,array,object(list),null服务端不允许传
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

class TypeCommand extends FilterAbstract {
	
	// 标识本类处理的类型
    public $commandType = 'type';
    // 支持的数据类型标识(复合list(object),array解析到具体项)
    public $typeItems = [
        'int',
        'string',
        'bool',
        'boolean',
        'array',
        // 'null', //不被server接受
    ];

    protected function checkInt($data) {
        return is_int($data)? true : false;
    }

    protected function checkString($data) {
        return is_string($data)? true : false;
    }

    protected function checkBool($data) {
        return is_bool($data)? true : false;
    }

    protected function checkBoolean($data) {
        return $this->checkBool($data);
    }

    protected function checkArray($data) {
        return is_array($data)? true : false;
    }

    /**
     * 检查后台传的Type值
     */
    public function checkTypeData($typeData = '') {
        return (in_array($typeData, $this->typeItems))? true : false;
    }

    /**
     * 默认类型值-检查
     * 可能参数比较简单，不用每数据类型值，单独建立方法，可使用重写此方法
     */
    public function checkDefaultTypeData($typeData, $data) {
        return true;
    }


}