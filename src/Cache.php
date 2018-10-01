<?php
namespace PhpApi;

use PhpApi\Cache\CacheFactory;

use PhpApi\Di;

/**
 * base cache
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class Cache extends CacheFactory {
	public $cache = '';

	public function __construct($ori = false, $config = array()) {
		try {
			$this->load($ori, $config);
		} catch (Exception $e) {
			trigger_error($e.message, E_USER_ERROR);
		}
		
	}

	/**
	 * model 手动初始化
	 */
	public function load($ori = false, $config = array()) {
		if (empty($config)) {
			// 加载配置
			$configObj = Di::single()->config;
			$configApp = $configObj->appConfig;
			// 更多扩展，更多支持 @todo
			if (isset($configApp['Cache']['Master'])) {
				$config = $configApp['Cache']['Master'];
			} else {
				return new Exception('Error config for DB!'); 
			}
		}
		parent::load($config);
		// 自动计算键名（根据类名hash）
		if(!$ori) {
			
		}
	}

}