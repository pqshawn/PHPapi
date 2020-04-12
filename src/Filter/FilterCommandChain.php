<?php
namespace PhpApi\Filter;


/**
 * Filter CommandChain
 * 过滤类的 命令链
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

class FilterCommandChain {
	
	private $commands = [];

	/**
	 * 加入命令
	 */

	public function addCommand($commandObj) {
		$this->commands[] = $commandObj;
	}
	/**
	 * 运行命令
	 * 如果有类型，则直接判断, 如果没有说明$param是个数组，请按照数组结构['xx' => 'xx','xx' => 'xx']
	 */
	public function runCommand($dataName, $dataStruct, $data) {
		foreach ($this->commands as $comObj) {
			$currentCommandType = $comObj->commandType;
			
			if (array_key_exists($currentCommandType, $dataStruct)) {
				// 类型值,如require=1
				$typeData = $dataStruct[$currentCommandType];
				// 普通处理
				$res = $comObj->check($dataName, $data, $typeData);
				// 及时抛出异常，不在继续处理
				if(!$res) throw new \Exception($comObj->retErrorMessageTpl, 10001); // 系统错误码@todo
			}
		}
	}


}