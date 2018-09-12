<?php
namespace PhpApi;

use PhpApi\Filter\FilterCommandChain;
/**
 * Filter 过滤代理类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author: Shawn Yu <pggq@outlook.com>
 */

class Filter {

	// 递归层数判断(list)，防止恶意无限层嵌套攻击服务器
	protected $filterLayerListCount = 3;
	protected $filterLayerListCurrent = 0; // 当前最大层数
	// 数组最大层数，
	protected $filterLayerArrayCount = 4;

	private $configCommandsObj = [
		'\PhpApi\Filter\Commands\RequireCommand',
		'\PhpApi\Filter\Commands\TypeCommand',
		'\PhpApi\Filter\Commands\SizeCommand',
		'\PhpApi\Filter\Commands\FormatCommand',

	];

	// 保留字
	private $reservedWords = [
		'require',
		'type',
		'size',
		'format',
		'regex',
		'data',
		'array',
		'list'
	];

	/**å
	 * 处理我们的格式
	 * 这里我们不把各个类型融合成数组，我们偏向设计型，每个参数的多类型进行检查，如果要在页面上逐行检查参数，这种方法
	 * 则避免无规律检查，我们设计时已经考虑性能，放心使用
	 * 
	 * 如果多维的，则拆成一维的@todo, 如果 一个参数type类型是array(list）类型，list类型的每个参数要拿到外面来
	 */

	public function start($data = []) {
		if (empty($data))  return true;
		// new commandChain
		$chainObj = new FilterCommandChain();
		// add the commandObj
		foreach ($this->configCommandsObj as $objVal) {
			$chainObj->addCommand(new $objVal);
		}

		$this->recursionRunEachCommand($chainObj, $data);
	}

	/**
	 * 递归数据检查
	 * 支持多种条件检查，支持多层纵深检查
	 * 
	 * @param object $chainObj 命令链对象
	 * @param array $variable 规则和数据整合在一块的数组
	 */
	public function recursionRunEachCommand($chainObj, $variable) {
		foreach ($variable as $key => $value) {
			// // 如果当前类型是List,用require和data来组合判断，则递归
			if (!isset($value['data']) && !isset($value['require'])) {
				$this->recursionRunEachCommand($chainObj, $value);
			} else {
				// 参数逐个执行开始
				$chainObj->runCommand($key, $value, $value['data']);
			}

		}
	}


	/**
	 * 规则和值组合在一块
     * 请看参照interface里一对非常复杂的多种类型数据完整事例， 实际构造中往往都很简单
	 */
	public function filterCombineParams(&$filerRequest, $params, $action) {
		if (empty($params)) throw new \Exception("Empty Param Or Invalid Json", 1);
		$this->filterLayerListCurrent++;
		if ($this->filterLayerListCurrent > $this->filterLayerListCount) {
			throw new \Exception("Check Your Data List,Over Deep " . $this->filterLayerListCount, 1);
		}
		$actionName = lcfirst($action); // @todo 方法名全局小驼峰

		$filerRequest = isset($filerRequest[$actionName])? $filerRequest[$actionName] : '';
		if (empty($filerRequest)) return true;
		foreach ($filerRequest as $key => &$value) {
			if (in_array($key, $this->reservedWords)) {
				throw new \Exception("Error Processing Request For Reserved Words As Name", 1);
			}
			if (isset($params[$key])) {
				// 普通情况直接赋值
				$value['data'] = $params[$key];
				// require必须存在，recursionRunEachCommand取标识用
				!isset($value['require'])? $value['require'] = 0 : '';
				// 如果是List类型
				if (isset($value['list']) && !empty($value['list'])) {
					// 递归
					$this->filterCombineParams($value, $params[$key], 'list');
				}
				// 如果是array类型
				if (isset($value['array']) && !empty($value['array'])) {
					// 数组函数单独处理,传三个参数，1数组名 2数组规则 3数据 4父规则 5转化后的list 
					$arrayToList = [];
					$arrayRule = (isset($value['array']) && is_array($value['array']))? $value['array'] : [];
					$parentRule = array(
						'format' => isset($value['format'])? $value['format'] : '',
						'regex' => isset($value['regex'])? $value['regex'] : '',
					);
					$this->filterCombineArray($key, $arrayRule, $value['data'], $parentRule, $arrayToList);
					// 用list替换数组组合里的该顶级数组
					if (!empty($arrayToList)) {
						$value = $arrayToList;
					}
				}
			}
			// 如果客户端没值键值，但是后台已做判断，则默认值是空
			else {
				// require为假，前台也没传，不再参与整合，unset
				if (!$value['require']) unset($filerRequest[$key]);
				else $value['data'] = '';
			}
		}		
	}

	/**
	 * 數組類型拆成list组合类型
	 * 分两种情况，array里面存在List，第二种，直接单一的值，List类型构造如filterCombineParams函数加工的，
	 * 单一的值，只用‘format’,'regex'构造即可
	 * 注：不规则数组里，程序不会强加某键值必须存在等check，除非，数组里每项List同样键构造,且用户controller有此规则
	 *
	 * @param string $arrayName 数组名称
	 * @param array $filterRule 规则数组
	 * @param array $data 数据
	 * @param array $parentRule 父亲规则
	 * @param array   &  $arrayToList  加工好的list
	 *
	 * [array_test] => Array
                        (
                            [require] => 1
                            [type] => array
                            [size] => 11
                            [format] => numletter
                            [array] => Array
                                (
                                    [tkey] => Array
                                        (
                                            [require] => 1
                                            [type] => string
                                            [size] => 11
                                            [format] => version
                                        )

                                    [tkey2] => Array
                                        (
                                            [require] => 1
                                            [type] => array
                                            [size] => 11
                                            [format] => numletter
                                        )

                                )

                            [data] => Array
                                (
                                    [0] => Array
                                        (
                                            [tkey] => tvalue
                                            [tkey2] => Array
                                                (
                                                    [0] => 1
                                                    [1] => 2
                                                    [2] => 3
                                                )

                                        )

                                    [1] => Array
                                        (
                                            [tkey] => tvalue
                                        )

                                    [2] => 3value
                                )

                        )
	 */
	public function filterCombineArray($arrayName, $filterRule, $data, $parentRule, &$arrayToList) {
		// 组合成list的每个条目的名称前缀
		$listNamePrefix = $arrayName . '/';
		$layerArrayCurrent = count(explode('/', $listNamePrefix)) - 1;
		if ($layerArrayCurrent > $this->filterLayerArrayCount) {
			throw new \Exception("Check Your Data Array,Over Deep " . $this->filterLayerArrayCount, 1);
		}

		if (!empty($data)) {
			foreach ($data as $key => $dataVal) {
				$currentListName = $listNamePrefix . $key;
				// 一、 如果第一种情况，数组里还有list,即包含字符串key,则递归，直到不是数组
				if (is_array($dataVal)) {
					// 规则里存在当前键名，则要用当前规则覆盖掉父规则，就像子类方法重写父类方法一样
					$sonRule = $parentRule;
					if (array_key_exists($key, $filterRule)) {
						$sonRule['format'] = isset($filterRule[$key]['format'])? $filterRule[$key]['format'] : (isset($parentRule['format'])? $parentRule['format'] : '');
						$sonRule['regex'] = isset($filterRule[$key]['regex'])? $filterRule[$key]['regex'] : (isset($parentRule['regex'])? $parentRule['regex'] : '');
					}
					$this->filterCombineArray($currentListName, $filterRule, $dataVal, $sonRule, $arrayToList);
				} else {
					// 二、判断是否存在规则里的键名1.
					if (array_key_exists($key, $filterRule)) {
						// 规则里存在当前键名，则要用当前规则覆盖掉父规则，就像子类方法重写父类方法一样
						$filterRule[$key]['format'] = isset($filterRule[$key]['format'])? $filterRule[$key]['format'] : (isset($parentRule['format'])? $parentRule['format'] : '');
						$filterRule[$key]['regex'] = isset($filterRule[$key]['regex'])? $filterRule[$key]['regex'] : (isset($parentRule['regex'])? $parentRule['regex'] : '');
						$arrayToList[$currentListName] = $filterRule[$key];
					} else {
						// 2.如果没键名，则按照统一的父亲规则,构造
						$arrayToList[$currentListName]['format'] = $parentRule['format'];
						$arrayToList[$currentListName]['regex'] = $parentRule['regex'];
					}
					$arrayToList[$currentListName]['data'] = $dataVal;
				}

			}
		}
	}
	
}