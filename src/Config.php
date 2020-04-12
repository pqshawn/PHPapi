<?php
namespace PhpApi;


/**
 * 配置类
 * 处理配置的支持类
 * 
 * 语言选择
 * 错误码启用
 * 各应用配置{
 *   数据库配置
 *   缓存配置
 *    ......
 * }
 * 
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */

class Config {

    public function __construct($configDir = '') {
        $this->configDir = $configDir;
    }

    /**
     * 当前应用名
     */
    public $appName = 'App';

    /**
     * @var configDir
     */
    private $configDir = '';

    /**
     * 语言包
     */
    public $lang = [];

    /**
     * 语言包路径
     */
    private $langDir = '';

    /**
     * 错误码
     */
    public $codeStatus = [
    ];

    /**
     * 系统码（预留），用户可覆盖
     */
    public $sysCodeStatus = [
        'EXCEPTION' => 0,
        'SYSTEM_WARNING_FOR_PARAMS' => 1, // 系统警告，对于一些参数可能影响系统，或不符合系统要求（面向开发人员）
        'CUSTOMER_WARNING_FOR_PARAMS' => 2, // 需要用户自定义的警告，可以展示到前台（面向客户）
        'SUCCESS' => 200,
        'NO_METHOD' => 404,
    ];

    /**
     * 用户自定义配置
     */
    public $config = [];

    /**
     * 当前App配置
     */
    public $appConfig = [];

    /**
     * 统一配置
     */
    public function main($appName) {
        $this->setAppName($appName);

        $this->loadConfig($appName);
        $this->loadLang($appName);
        $this->setTimeZone($appName);
        $this->loadCodeStatus($appName);
    }

    /**
     * 通用加载文件
     */
    protected function loadConfig($appName = '') {
        $configPacket =  $this->configDir . DIRECTORY_SEPARATOR . $appName . '.php';
        if (is_file($configPacket)) {
            // $this->config[$appName] = @include($configPacket);
            $this->appConfig = $this->config[$appName] = @include($configPacket);
        }
    }

    /**
     * 设置应用名
     */
    protected function setAppName($appName = '', $default = 'App') {
        if (!empty($appName)) {
            $this->appName = $appName;
        } else {
            $this->appName = $default;
        }
    }

    /**
     * 获取应用名
     */
    protected function getAppName() {
        return $this->appName;
    }
    
    /**
     * 
     * 加载语言包
     */
    protected function loadLang($appName = '') {
        $langDir = isset($this->config[$appName]['LandDir'])? $this->config[$appName]['LandDir'] : '';
        $langName = isset($this->config[$appName]['Land'])? $this->config[$appName]['Land'] : 'zh_cn';

        // 加载系统语言包
        $langPacket =  dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Lang' . DIRECTORY_SEPARATOR . $langName . '.php';
        if (is_file($langPacket)) {
            $this->lang = @include($langPacket);
        }
        // 加载用户自定义语言包
        $langUser =  $langDir . DIRECTORY_SEPARATOR . $langName . '.php';
        if (is_file($langUser)) {
            $this->lang = array_merge(@include($langUser), $this->lang);
        }
    }

    /**
     * 时区设置
     */
    protected function setTimeZone($appName = '') {
        if (isset($this->config[$appName]['TimeZone'])) {
            // @todo 时区验证
            date_default_timezone_set($this->config[$appName]['TimeZone']);
        } else {
            date_default_timezone_set('Asia/Shanghai');
        }
    }

    /**
     * 
     * 加载错误码
     */
    protected function loadCodeStatus($appName = '') {
        $fileName = isset($this->config[$appName]['CodeStatus'])? $this->config[$appName]['CodeStatus'] : '';
        // 加载系统语言包
        $codeStatusFile =  $this->configDir . DIRECTORY_SEPARATOR . $fileName . '.php';
        if (is_file($codeStatusFile)) {
            $codeStatus = @include($codeStatusFile);
            if (is_array($codeStatus)) {
                $this->codeStatus = array_merge($codeStatus, $this->codeStatus);
            }
        }
    }

}

