<?php
namespace PhpApi\Cache;

use PhpApi\Standard\Cache\CacheInterface;
use PhpApi\Standard\Factory\FactoryInterface;

use PhpApi\Di;

/**
 * cache factory
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <Ldos.net>
 */

abstract class CacheFactory implements FactoryInterface, CacheInterface {
	// 默认redis引擎
	public $cache_driver = 'redis';
	public $cache = '';
	public $alias = '';
	
	public function __construct($confCache = array()) {
		$this->load($confCache);
	}

	public function load($confCache = array()) {
		if (empty($confCache)) return null;
		if (isset($confCache['type'])) {
			$this->cache_driver = $cacheDriver = $confCache['type'];
			$cache_driver = ucfirst($cacheDriver);

			$alias = isset($confCache['alias'])? $confCache['alias'] : $cacheDriver;

			// check 某引擎（可能有同引擎不同库的情况，对象也是不同的）对象已经建立，则不需要再重新加载
			$this->alias = $alias;
			// 一定要赋值到类属性cache
			if (is_object($this->cache = Di::single()->$alias)) {
				return true;
			}

			Di::single()->$alias = "\\PhpApi\\Cache\\{$cache_driver}";
		} else {
			// 用默认引擎作键名
			$defaultCache = $this->cache_driver;
			$ucCacheDriver = ucfirst($defaultCache);
			Di::single()->$defaultCache = "\\PhpApi\\Cache\\{$ucCacheDriver}";
		}
		
		$this->cache = $this->router();
		$this->cache->setConfig($confCache);
	}

	public function __destruct() {

	}

	public function router() {
		$alias = empty($this->alias)? $this->cache_driver : $this->alias;
		return Di::single()->$alias;
	}

	public function get($key) {
		$res = $this->cache->get($key);
		return $res;
	}

    /**
     * 设置缓存
     * 
     * @param string $key
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public function set($key, $value, $expire = 3600) {
    	$res = $this->cache->set($key, $value, $expire);
		return $res;
    }

    /**
     * 删除缓存
     * 
     * @param ... $keys
     * @return boolean
     */
    public function delete(...$keys) {
    	$res = $this->cache->delete($keys);
		return $res;
    }

    /**
     * Set the string value in argument as value of the key, with a time to live. PSETEX uses a TTL in milliseconds.
     * 
     */
    public function setEx(){}
    public function pSetEx(){}

    /**
     * Set the string value in argument as value of the key if the key doesn't already exist in the database.
     * 
     */
    public function setNx(){}

    /**
     * Remove specified keys.
     */
    public function unlink(){}


    /**
     * Start the background rewrite of AOF (Append-Only File)
     * 
     */
    public function bgRewriteAOF(){}

    /**
     * Asynchronously save the dataset to disk (in background)
     * 
     */
    public function bgSave(){}

    /**
     *  Get or Set the Redis server configuration parameters.
     * 
     */
    public function config(){}


    /**
     *  Return the number of keys in selected database.
     * 
     */
    public function dbSize(){}


    /**
     * Remove all keys from all databases.
     * 
     */
    public function flushAll(){}


    /**
     * Remove all keys from the current database.
     * 
     */
    public function flushDb(){}


    /**
     *  Returns the timestamp of the last disk save.
     * 
     */
    public function lastSave(){}


    /**
     *  Reset the stats returned by info method.
     * 
     */
    public function resetStat(){}
   
}