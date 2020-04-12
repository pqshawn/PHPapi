<?php
namespace PhpApi\Filter\Commands;

use PhpApi\Standard\Filter\FilterAbstract;

/**
 * Filter Command----Require
 * 判断是否传参，是否为空
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

class RequireCommand extends FilterAbstract {
	
	// 标识本类处理的类型
	public $commandType = 'require';

	public $retStatus = 1;

	public $retErrorMessageTpl = 'The {parameter} is required!';

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
     * 重写了父类check
     */
    public function check($dataName, $data, $typeData) {
        // 类型值为0，false,null等,则不检查
        if (empty($typeData)) return true;
        // 注意，有可能传值0，0.0，null，true等类型，所以这里默认判断字符串空，结下来交给typeCommand处理
    	if ($data !== '') {
    		return true;
    	} else {
    		$this->retStatus();
    		$this->retErrorMessageTpl($dataName);
    		return false;
    	}

    }

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
    		$this->retErrorMessageTpl = str_replace('{parameter}', $parameterName, $this->retErrorMessageTpl);
    	}
    }

    /**
     * 检查后台传的Type值，如require=true,  true,即是Type值 
     */
    public function checkTypeData($typeData = '') {
        return (is_bool($typeData) || in_array($typeData, [0, 1]))? true : false;
    }

    /**
     * 默认类型值-检查
     * 可能类型值处理比较简单，不用每数据类型值单独建立方法，统一判断即可
     */
    public function checkDefaultTypeData($typeData, $data) {
        return true;
    }


}