<?php
namespace PhpApi\Standard\Filter;

use PhpApi\Standard\Filter\FilterInterface;

/**
 * Filter Command----Abstract抽象类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

abstract class FilterAbstract implements FilterInterface {
	
	// 标识本类处理的类型
    protected $commandType = '';
    // 支持的类型数据格式
    protected $typeItems = [];

	public $retStatus = 1;

	public $retErrorMessageTpl = 'The {commandType} of {parameter} - Failed to pass the inspection!';


	/**
     * 获取类型
     * 
     * @param string $key
     * @return mixe\d 
     */
    public function get($key) {
    }

    /**
     * 设置类型
     * 
     * @param string $key
     * @param string $value
     * @param string $expire
     * @return boolea/n
     */
    public function set($key, $value) {
    }

    /**
     * 普通判断
     */
    public function check($dataName, $data, $typeData) {
        // 类型值为0，false,null等,则不检查
        if (empty($typeData)) return true;
        // typeData 判断
        if ($express = $this->checkTypeData($typeData)) {
            if (is_numeric($typeData)) {
                //  类型值为数字，走默认的
                $res = $this->checkDefaultTypeData($typeData, $data);
                if ($res) return true;
            }
            // 特殊处理，类型值是个复合类型，如 regex|/^v[\d+]\.[\d+].[\d+]$/ 
            elseif(is_array($express)) {
                $funcName = 'check' . ucfirst($express[0]);
                $res = $this->$funcName($data, $express[1]);
                if ($res) return true;
            }
            else {
                $funcName = 'check' . ucfirst($typeData);
                $res = $this->$funcName($data);
                if ($res) return true;
            }
        }
        $this->retStatus();
        $this->retErrorMessageTpl($dataName);
        return false;
    }

    /**
     * 检查类型数据格式
     * 
     * @return boolean
     */
    abstract public function checkTypeData($typeData = '');

    /**
     * 子类必须重写该方法
     * 子类重写此方法时，可以什么都不做，直接返回true,如果类型值存在多种情况.
     * 如果多个类型值，但处理起来并不适用每个类型值分别建立方法，那就统一用默认方法，当然你也可以重写check方法
     * 
     * @return boolean
     */
    abstract public function checkDefaultTypeData($typeData, $data);


    /**
     * 返回状态
     */
    public function retStatus() {
    	$this->retStatus = 'INVALID_ARGUMENT';
    }

    /**
     * 返回消息
     */
    public function retErrorMessageTpl($parameterName) {
    	if (!empty($parameterName)) {
    		$this->retErrorMessageTpl = str_replace(['{commandType}', '{parameter}'], [$this->commandType, $parameterName], $this->retErrorMessageTpl);
    	}
    }

    /**
     * 不存在的方法（主要指类型值不存在），则访问默认方法
     * 暂屏蔽此方法，因在运行时占用大量内存（检查文件）
     */
    // public function __call($name, $arguments) {
    //     return $this->checkDefaultTypeData($arguments);
    // }

    


}