<?php
namespace PhpApi\Response;

use PhpApi\Standard\Response\ResponseAbstract;

/**
 * 加密响应
 *
 * @copyright (c)Ldos.net All rights reserved.
 * @author Shawn Yu <pggq@outlook.com>
 */


class CryptoJsonResponse extends ResponseAbstract {

    protected $contentType = 'application/crypto-json';

    protected $cryptType = 'AesCrypto';
    /**
     * @var 默认压缩
     */
    protected $compressFlag = true;

    protected $compressType = 'ZLIB';

    protected $data = [];

    /**
     * @var 数组转化成的字符串
     */
    protected $dataWrapperString = '{}';
        
    protected function setHeaders() {
        @header('Content-type: ' . $this->contentType . ';charset=utf-8');
    }

    protected function getHeaders() {

    }

    protected function security() {

    }

    /**
     * 加密适配
     */
    protected function encrypt($cryptObj = null, $res = '') {
        return $cryptObj->comEncrypt($res);
    }

    /**
     * 解密适配
     */
    protected function decrypt($cryptObj = null, $res = '') {
        return $cryptObj->comDecrypt($res);
    }

    /**
     * 压缩
     * 暂支持ZLIB类型
     */
    protected function compress($type = 'ZLIB', $res = '') {
        $result = $res;
        if ($this->compressFlag && $type == 'ZLIB') {
            $result = gzcompress($res, -1,  ZLIB_ENCODING_DEFLATE);
        }
        return $result;
    }

    protected function uncompress($type = 'ZLIB', $res = '') {
        $result = $res;
        if ($type == 'ZLIB') {
            $result = gzuncompress($res);
        }
        return $result;
    }
       
    public function output() {
        // 设置头
        $this->setHeaders();
        // 获取&处理数据
        $dataWrapper = $this->getBody();
        // 添加签名预置位
        $dataWrapper['sign'] = '{sign}';
        // 排序
        krsort($dataWrapper);
        $this->dataWrapperString = json_encode($dataWrapper, JSON_UNESCAPED_UNICODE);
        // 压缩
        // $compressData = $this->compress($this->compressType, $this->dataWrapperString);
        // 加密
        $cryptType = $this->cryptType;
        $cryptClassName = '\\PhpApi\\Crypto\\' . $cryptType;
        $cryptObj = new $cryptClassName();
        $cryptString = $this->encrypt($cryptObj, $this->dataWrapperString);

        echo ($cryptString);
    }

 }