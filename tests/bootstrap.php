<?php
namespace PhpApi\Tests;

use PhpApi\Di;

/**
 * bootstrap
 * 预先注入所需要的各种支持类
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author:Shawn Yu <pggq@outlook.com>
 */

defined('PHPAPI_ROOT') || define('PHPAPI_ROOT', dirname(__FILE__));

require PHPAPI_ROOT.'/../vendor/autoload.php';

/**
 * 基本类的加载 a.依赖注入 b.请求 c.响应 d.router类 e.主启动类（kernel）f.异常 g.校验
 * 先预设，至于什么时候引进来，在当时的需要的类中use引进命名空间，进行实例化
 */
Di::single()->request = '\\PhpApi\\Request';
Di::single()->response = '\\PhpApi\\Response';
// Di()->router = '\\PhpApi\\Router';
// Di()->exception = '\\PhaApi\\Exception';
// Di()->requestRule = '\\PhaApi\\Request\\Rule';
// /**
//  * 支撑类的加载
//  * a.配置，b.日志  c.语言包  d.Dao类，e.过滤类，f.orm-model层 g.cache类  h.控制层 i.debug j.service领域层 k.时区
//  */
// Di()->config = '\\PhaApi\\Config';
// Di()->logger = '\\PhaApi\\Logger';
// Di()->lang = '\\PhaApi\\Lang';
// Di()->filter= '\\PhaApi\\Filter';
// Di()->model = '\\PhaApi\\model';
// Di()->cache = '\\PhaApi\\Cache';
// Di()->controller = '\\PhaApi\\Controller';
// Di()->debug = '\\PhaApi\\Debug';
// Di()->service = '\\PhaApi\\Service';
// Di()->timezone = '\\PhaApi\\Timezone';





