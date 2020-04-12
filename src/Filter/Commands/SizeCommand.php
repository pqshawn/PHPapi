<?php
namespace PhpApi\Filter\Commands;

use PhpApi\Standard\Filter\FilterAbstract;

/**
 * Filter Command----Size
 * 现只支持utf-8，如有需要请扩展
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

class SizeCommand extends FilterAbstract {
	
	// 标识本类处理的类型
    public $commandType = 'size';

    /**
     * 检查后台传的Type值
     * 只能是整型
     */
    public function checkTypeData($typeData = '') {
        return (is_numeric($typeData))? true : false;
    }

    /**
     * 默认类型值-检查
     * 可能参数比较简单，不用每数据类型值，单独建立方法，可使用重写此方法
     */
    public function checkDefaultTypeData($typeData, $data) {
        $len = mb_strlen($data);
        // 大小在范围之内
        if ($len <= $typeData) {
            return true;
        }
        return false;
    }


}