<?php
namespace PhpApi\Standard\Response;

/**
 * response abstract
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


 abstract class ResponseAbstract {

    protected $contentType = 'application/json';

    protected $data = [];

    // 外包装
    protected $dataWrapper = array('data' => []);
        
    abstract protected function setHeaders();

    abstract protected function getHeaders();

    abstract protected function security();

    abstract protected function encrypt($type = 'AES');

    abstract protected function decrypt($type = 'AES');

    abstract protected function compress($type = 'ZLIB');

    abstract protected function uncompress($type = 'ZLIB');

    /**
     * 返回数据类型array的body
     * 
     * @return array $result
     */
    protected function getBody() {
        $result = $this->dataWrapper;

        if (!isset($this->dataWrapper['ret'])) {
            $retCode = array('ret' => 200, 'msg' => 'success');
            $result = array_merge($retCode,$this->dataWrapper);
        }

        return $result;
    }

    public function setBody($data = [], ...$params) {
        $this->data = $data;

        // 如有用户自定义语言包，重新计算下
        $configObj = \PhpApi\Di::single()->config;
        $lang = $configObj->lang;
        $codeStatus = $configObj->codeStatus;
        $appName = $configObj->appName;
        if(isset($data['ret']) && is_int($data['ret'])) {
            $this->dataWrapper = $data;
            return true;
        }
        elseif (isset($data['retKey']) && is_string($data['retKey'])) {
            // 重新定义错误码
            if (isset($codeStatus[$appName][$data['retKey']])) {
                $this->dataWrapper['ret'] = $codeStatus[$appName][$data['retKey']];
            } // 系统的错误码找下
            elseif (isset($codeStatus[$data['retKey']])) {
                $this->dataWrapper['ret'] = $codeStatus[$data['retKey']];
            }
            // 重新定义消息
            if (isset($lang[$data['retKey']])) {
                $this->dataWrapper['msg'] = $lang[$data['retKey']] ;
            }
            $this->dataWrapper['retKey'] = $data['retKey'];
            unset($data['retKey']);
        }
        if (!empty($data)) $this->dataWrapper['data'] = $data;

    }
        
    abstract public function output();

 }