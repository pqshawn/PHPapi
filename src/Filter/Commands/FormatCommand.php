<?php
namespace PhpApi\Filter\Commands;

use PhpApi\Standard\Filter\FilterAbstract;

/**
 * Filter Command----Format
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

class FormatCommand extends FilterAbstract {
	
	// 标识本类处理的类型
    public $commandType = 'format';

    // 支持的类型数据格式
    protected $typeItems = ['version', 'numletter', 'int', 'phone'];

    /**
     * 检查后台传的Type值
     * 只能是整型
     */
    public function checkTypeData($typeData = '') {
        if (strpos($typeData, '|') !== false) {
            $express = explode('|', $typeData);
            return $express;
        }
        return (in_array($typeData, $this->typeItems))? true : false;
    }

    protected function checkVersion($data) {
        $pattern = '/^v\d+\.\d+\.\d+$/';
        return $this->checkPattern($pattern, $data);
    }

    protected function checkNumletter($data) {
        $pattern = '/^\w+$/';
        return $this->checkPattern($pattern, $data);
    }

    protected function checkInt($data) {
        $pattern = '/^\d+$/';
        return $this->checkPattern($pattern, $data);
    }

    protected function checkPhone($data) {
        $pattern = '/^1(\d){10}$/';
        return $this->checkPattern($pattern, $data);
    }

    protected function checkRegex($data, $express) {
        //  更改提示语
        $this->retErrorMessageTpl = 'The {commandType}(Regex Expression) of {parameter} - Failed to pass the inspection!';
        return $this->checkPattern($express, $data);
    }

    protected function checkPattern($pattern = '/\w+/', $data) {
        if (is_array($data)) {
            foreach ($data as $v) {
                if (!preg_match($pattern, $data)) {
                    return false;
                }
            }
        } else {
            if (!preg_match($pattern, $data)) return false;
        }
        return true;
    }

    /**
     * 默认类型值-检查
     * 可能参数比较简单，不用每数据类型值，单独建立方法，可使用重写此方法
     */
    public function checkDefaultTypeData($typeData, $data) {
        return true;
    }


}